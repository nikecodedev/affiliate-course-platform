<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\CourseLessonAttachment;
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
            ->ordered()
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
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $course = new Course($request->except(['image']));
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = $imagePath;
        }

        $course->save();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        $course->load(['modules.lessons.attachments']);
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
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $course->fill($request->except(['image']));

        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = $imagePath;
        }

        $course->save();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        // Check if course has plans associated
        if ($course->plans()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete course with associated plans.');
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
     * Toggle course active status
     */
    public function toggleStatus(Course $course)
    {
        $course->is_active = !$course->is_active;
        $course->save();

        return response()->json([
            'success' => true,
            'is_active' => $course->is_active,
        ]);
    }

    // Module Management Methods

    /**
     * Store a newly created module
     */
    public function storeModule(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $module = new CourseModule($request->all());
        $module->course_id = $course->id;
        $module->save();

        return redirect()->back()
            ->with('success', 'Module created successfully.');
    }

    /**
     * Update the specified module
     */
    public function updateModule(Request $request, Course $course, CourseModule $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $module->update($request->all());

        return redirect()->back()
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module
     */
    public function destroyModule(Course $course, CourseModule $module)
    {
        $module->delete();

        return redirect()->back()
            ->with('success', 'Module deleted successfully.');
    }

    // Lesson Management Methods

    /**
     * Store a newly created lesson
     */
    public function storeLesson(Request $request, Course $course, CourseModule $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_embed' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lesson = new CourseLesson($request->all());
        $lesson->module_id = $module->id;
        $lesson->save();

        return redirect()->back()
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Update the specified lesson
     */
    public function updateLesson(Request $request, Course $course, CourseModule $module, CourseLesson $lesson)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_embed' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lesson->update($request->all());

        return redirect()->back()
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified lesson
     */
    public function destroyLesson(Course $course, CourseModule $module, CourseLesson $lesson)
    {
        // Delete attachments
        foreach ($lesson->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $lesson->delete();

        return redirect()->back()
            ->with('success', 'Lesson deleted successfully.');
    }
}

