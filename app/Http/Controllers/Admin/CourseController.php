<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of courses
     */
    public function index()
    {
        $courses = Course::with(['modules.lessons'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseData = $request->except(['image']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $courseData['image'] = $imagePath;
        }

        $course = Course::create($courseData);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        $course->load(['modules.lessons']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the course
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseData = $request->except(['image']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $imagePath = $request->file('image')->store('courses', 'public');
            $courseData['image'] = $imagePath;
        }

        $course->update($courseData);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        // Check if course has modules
        if ($course->modules()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete course with existing modules.');
        }

        // Delete image
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Toggle course status
     */
    public function toggleStatus(Course $course)
    {
        $course->is_active = !$course->is_active;
        $course->save();

        return response()->json([
            'success' => true,
            'status' => $course->is_active,
        ]);
    }
}