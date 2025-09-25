<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a module
     */
    public function index(Course $course, Module $module)
    {
        $lessons = $module->lessons()->orderBy('order')->paginate(15);
        return view('admin.lessons.index', compact('course', 'module', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson
     */
    public function create(Course $course, Module $module)
    {
        return view('admin.lessons.create', compact('course', 'module'));
    }

    /**
     * Store a newly created lesson
     */
    public function store(Request $request, Course $course, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar|max:10240', // 10MB max
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lessonData = $request->except(['attachment']);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('lessons/attachments', 'public');
            $lessonData['attachment'] = $attachmentPath;
        }

        $lessonData['module_id'] = $module->id;

        // If no order is specified, set it to the next available order
        if (!isset($lessonData['order']) || $lessonData['order'] === null) {
            $lessonData['order'] = $module->lessons()->max('order') + 1;
        }

        Lesson::create($lessonData);

        return redirect()->route('admin.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified lesson
     */
    public function show(Course $course, Module $module, Lesson $lesson)
    {
        return view('admin.lessons.show', compact('course', 'module', 'lesson'));
    }

    /**
     * Show the form for editing the lesson
     */
    public function edit(Course $course, Module $module, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'module', 'lesson'));
    }

    /**
     * Update the specified lesson
     */
    public function update(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar|max:10240',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lessonData = $request->except(['attachment']);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($lesson->attachment && Storage::disk('public')->exists($lesson->attachment)) {
                Storage::disk('public')->delete($lesson->attachment);
            }

            $attachmentPath = $request->file('attachment')->store('lessons/attachments', 'public');
            $lessonData['attachment'] = $attachmentPath;
        }

        $lesson->update($lessonData);

        return redirect()->route('admin.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified lesson
     */
    public function destroy(Course $course, Module $module, Lesson $lesson)
    {
        // Delete attachment if exists
        if ($lesson->attachment && Storage::disk('public')->exists($lesson->attachment)) {
            Storage::disk('public')->delete($lesson->attachment);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Update lesson order
     */
    public function updateOrder(Request $request, Course $course, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->lessons as $lessonData) {
            Lesson::where('id', $lessonData['id'])
                ->where('module_id', $module->id)
                ->update(['order' => $lessonData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson order updated successfully.'
        ]);
    }

    /**
     * Download lesson attachment
     */
    public function downloadAttachment(Course $course, Module $module, Lesson $lesson)
    {
        if (!$lesson->attachment || !Storage::disk('public')->exists($lesson->attachment)) {
            abort(404, 'Attachment not found.');
        }

        return Storage::disk('public')->download($lesson->attachment, $lesson->getAttachmentNameAttribute());
    }
}