<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules for a course
     */
    public function index(Course $course)
    {
        $modules = $course->modules()->orderBy('order')->paginate(15);
        return view('admin.modules.index', compact('course', 'modules'));
    }

    /**
     * Show the form for creating a new module
     */
    public function create(Course $course)
    {
        return view('admin.modules.create', compact('course'));
    }

    /**
     * Store a newly created module
     */
    public function store(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $moduleData = $request->all();
        $moduleData['course_id'] = $course->id;

        // If no order is specified, set it to the next available order
        if (!isset($moduleData['order']) || $moduleData['order'] === null) {
            $moduleData['order'] = $course->modules()->max('order') + 1;
        }

        Module::create($moduleData);

        return redirect()->route('admin.modules.index', $course)
            ->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified module
     */
    public function show(Course $course, Module $module)
    {
        $module->load(['lessons']);
        return view('admin.modules.show', compact('course', 'module'));
    }

    /**
     * Show the form for editing the module
     */
    public function edit(Course $course, Module $module)
    {
        return view('admin.modules.edit', compact('course', 'module'));
    }

    /**
     * Update the specified module
     */
    public function update(Request $request, Course $course, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $module->update($request->all());

        return redirect()->route('admin.modules.index', $course)
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module
     */
    public function destroy(Course $course, Module $module)
    {
        // Check if module has lessons
        if ($module->lessons()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete module with existing lessons.');
        }

        $module->delete();

        return redirect()->route('admin.modules.index', $course)
            ->with('success', 'Module deleted successfully.');
    }

    /**
     * Update module order
     */
    public function updateOrder(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->modules as $moduleData) {
            Module::where('id', $moduleData['id'])
                ->where('course_id', $course->id)
                ->update(['order' => $moduleData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Module order updated successfully.'
        ]);
    }
}