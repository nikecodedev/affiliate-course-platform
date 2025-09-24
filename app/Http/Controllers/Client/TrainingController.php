<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /**
     * Show training dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'You need an active invoice to access training courses.');
        }
        
        // Get available courses
        $courses = Course::where('status', true)
            ->with(['modules.lessons'])
            ->get();
        
        // Calculate progress for each course
        foreach ($courses as $course) {
            $course->progress = $user->getCourseProgress($course->id);
        }
        
        return view('client.training.index', compact('courses'));
    }

    /**
     * Show course details with modules and lessons
     */
    public function showCourse(Course $course)
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            return redirect()->route('client.training.index')
                ->with('error', 'You need an active invoice to access training courses.');
        }
        
        $course->load(['modules.lessons']);
        
        // Calculate progress for each module
        foreach ($course->modules as $module) {
            $module->progress = $module->getProgressForUser($user->id);
        }
        
        $course->progress = $user->getCourseProgress($course->id);
        
        return view('client.training.course', compact('course'));
    }

    /**
     * Show lesson details
     */
    public function showLesson(Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            return redirect()->route('client.training.index')
                ->with('error', 'You need an active invoice to access training courses.');
        }
        
        // Verify lesson belongs to module and course
        if ($lesson->module_id !== $module->id || $module->course_id !== $course->id) {
            abort(404);
        }
        
        // Get previous and next lessons
        $previousLesson = $module->lessons()
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();
            
        $nextLesson = $module->lessons()
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();
        
        // Check if lesson is completed
        $isCompleted = $lesson->isCompletedByUser($user->id);
        
        return view('client.training.lesson', compact('course', 'module', 'lesson', 'previousLesson', 'nextLesson', 'isCompleted'));
    }

    /**
     * Mark lesson as completed
     */
    public function completeLesson(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            return response()->json([
                'success' => false,
                'message' => 'You need an active invoice to access training courses.'
            ], 403);
        }
        
        // Verify lesson belongs to module and course
        if ($lesson->module_id !== $module->id || $module->course_id !== $course->id) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found.'
            ], 404);
        }
        
        // Mark lesson as completed
        $lesson->markAsCompletedForUser($user->id);
        
        // Calculate updated progress
        $moduleProgress = $module->getProgressForUser($user->id);
        $courseProgress = $user->getCourseProgress($course->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as completed!',
            'module_progress' => $moduleProgress,
            'course_progress' => $courseProgress
        ]);
    }

    /**
     * Download lesson attachment
     */
    public function downloadAttachment(Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            abort(403, 'You need an active invoice to access training courses.');
        }
        
        // Verify lesson belongs to module and course
        if ($lesson->module_id !== $module->id || $module->course_id !== $course->id) {
            abort(404);
        }
        
        if (!$lesson->attachment || !\Storage::disk('public')->exists($lesson->attachment)) {
            abort(404, 'Attachment not found.');
        }

        return \Storage::disk('public')->download($lesson->attachment, $lesson->getAttachmentNameAttribute());
    }

    /**
     * Get course progress data for AJAX
     */
    public function getProgress(Course $course)
    {
        $user = Auth::user();
        
        // Check if user has active invoice
        if (!$user->hasActiveInvoice()) {
            return response()->json([
                'success' => false,
                'message' => 'You need an active invoice to access training courses.'
            ], 403);
        }
        
        $course->load(['modules.lessons']);
        
        $progress = [
            'course_progress' => $user->getCourseProgress($course->id),
            'modules' => []
        ];
        
        foreach ($course->modules as $module) {
            $progress['modules'][] = [
                'id' => $module->id,
                'title' => $module->title,
                'progress' => $module->getProgressForUser($user->id),
                'total_lessons' => $module->getLessonsCountAttribute(),
                'completed_lessons' => $module->getCompletedLessonsCountForUser($user->id)
            ];
        }
        
        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }
}