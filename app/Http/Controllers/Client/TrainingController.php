<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use App\Models\ClientCourseProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /**
     * Show training dashboard
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Check if client has course access
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        // Get client's course progress
        $courseProgress = $client->courseProgress()
            ->with(['course', 'lesson', 'module'])
            ->get()
            ->groupBy('course_id');
        
        // Get available courses
        $availableCourses = Course::where('is_active', true)
            ->with(['modules.lessons'])
            ->get();
        
        // Calculate overall progress
        $overallProgress = $this->calculateOverallProgress($client, $availableCourses);
        
        // Get recent activity
        $recentActivity = $client->courseProgress()
            ->with(['course', 'lesson'])
            ->latest()
            ->limit(10)
            ->get();
        
        return view('client.training.index', compact(
            'courseProgress',
            'availableCourses',
            'overallProgress',
            'recentActivity'
        ));
    }

    /**
     * Show all available courses
     */
    public function courses()
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        $courses = Course::where('is_active', true)
            ->with(['modules.lessons'])
            ->withCount(['modules', 'lessons'])
            ->get();
        
        // Get progress for each course
        $courseProgress = $client->courseProgress()
            ->get()
            ->groupBy('course_id');
        
        return view('client.training.courses', compact('courses', 'courseProgress'));
    }

    /**
     * Show specific course
     */
    public function showCourse(Course $course)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        if (!$course->is_active) {
            return redirect()->route('client.training.courses')
                ->with('error', 'Este curso não está disponível.');
        }
        
        // Load course with modules and lessons
        $course->load(['modules.lessons' => function($query) {
            $query->orderBy('order');
        }]);
        
        // Get client's progress for this course
        $progress = $client->courseProgress()
            ->where('course_id', $course->id)
            ->get()
            ->keyBy('lesson_id');
        
        // Calculate course progress
        $totalLessons = $course->lessons->count();
        $completedLessons = $progress->where('completed', true)->count();
        $courseProgressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        
        return view('client.training.course-show', compact(
            'course',
            'progress',
            'courseProgressPercentage',
            'completedLessons',
            'totalLessons'
        ));
    }

    /**
     * Show specific module
     */
    public function showModule(Course $course, CourseModule $module)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        if ($module->course_id !== $course->id) {
            abort(404);
        }
        
        // Load module with lessons
        $module->load(['lessons' => function($query) {
            $query->orderBy('order');
        }]);
        
        // Get client's progress for this module
        $progress = $client->courseProgress()
            ->where('course_id', $course->id)
            ->where('module_id', $module->id)
            ->get()
            ->keyBy('lesson_id');
        
        return view('client.training.module-show', compact('course', 'module', 'progress'));
    }

    /**
     * Show specific lesson
     */
    public function showLesson(Course $course, CourseModule $module, CourseLesson $lesson)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        if ($lesson->module_id !== $module->id || $module->course_id !== $course->id) {
            abort(404);
        }
        
        // Get client's progress for this lesson
        $progress = $client->courseProgress()
            ->where('course_id', $course->id)
            ->where('lesson_id', $lesson->id)
            ->first();
        
        // Load lesson with attachments
        $lesson->load('attachments');
        
        // Get previous and next lessons
        $previousLesson = CourseLesson::where('module_id', $module->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();
        
        $nextLesson = CourseLesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();
        
        return view('client.training.lesson-show', compact(
            'course',
            'module',
            'lesson',
            'progress',
            'previousLesson',
            'nextLesson'
        ));
    }

    /**
     * Update lesson progress
     */
    public function updateProgress(Request $request, Course $course, CourseModule $module, CourseLesson $lesson)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        $validator = \Validator::make($request->all(), [
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'time_spent' => 'nullable|integer|min:0',
            'last_position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Get or create progress record
        $progress = $client->courseProgress()
            ->where('course_id', $course->id)
            ->where('lesson_id', $lesson->id)
            ->first();
        
        if (!$progress) {
            $progress = $client->courseProgress()->create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'lesson_id' => $lesson->id,
                'progress_percentage' => $request->progress_percentage,
                'time_spent' => $request->time_spent ?? 0,
                'last_position' => $request->last_position ?? 0,
            ]);
        } else {
            $progress->updateProgress(
                $request->progress_percentage,
                $request->time_spent ?? 0
            );
            
            if ($request->has('last_position')) {
                $progress->update(['last_position' => $request->last_position]);
            }
        }
        
        return response()->json([
            'success' => true,
            'progress' => $progress,
            'completed' => $progress->completed
        ]);
    }

    /**
     * Mark lesson as completed
     */
    public function completeLesson(Request $request, Course $course, CourseModule $module, CourseLesson $lesson)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        // Get or create progress record
        $progress = $client->courseProgress()
            ->where('course_id', $course->id)
            ->where('lesson_id', $lesson->id)
            ->first();
        
        if (!$progress) {
            $progress = $client->courseProgress()->create([
                'course_id' => $course->id,
                'module_id' => $module->id,
                'lesson_id' => $lesson->id,
                'completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
            ]);
        } else {
            $progress->markAsCompleted();
        }
        
        // Check if course is completed
        $totalLessons = $course->lessons->count();
        $completedLessons = $client->courseProgress()
            ->where('course_id', $course->id)
            ->where('completed', true)
            ->count();
        
        $courseCompleted = $totalLessons === $completedLessons;
        
        return response()->json([
            'success' => true,
            'progress' => $progress,
            'course_completed' => $courseCompleted,
            'completed_lessons' => $completedLessons,
            'total_lessons' => $totalLessons
        ]);
    }

    /**
     * Show client's progress overview
     */
    public function progress()
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        // Get all courses with progress
        $courses = Course::where('is_active', true)
            ->with(['modules.lessons'])
            ->get();
        
        $courseProgress = [];
        
        foreach ($courses as $course) {
            $totalLessons = $course->lessons->count();
            $completedLessons = $client->courseProgress()
                ->where('course_id', $course->id)
                ->where('completed', true)
                ->count();
            
            $progressPercentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
            
            $courseProgress[] = [
                'course' => $course,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'progress_percentage' => $progressPercentage,
                'completed' => $progressPercentage === 100,
            ];
        }
        
        // Get recent activity
        $recentActivity = $client->courseProgress()
            ->with(['course', 'lesson'])
            ->latest()
            ->limit(20)
            ->get();
        
        return view('client.training.progress', compact('courseProgress', 'recentActivity'));
    }

    /**
     * Show certificates
     */
    public function certificates()
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        // Get completed courses
        $courses = Course::where('is_active', true)->get();
        $certificates = [];
        
        foreach ($courses as $course) {
            $totalLessons = $course->lessons->count();
            $completedLessons = $client->courseProgress()
                ->where('course_id', $course->id)
                ->where('completed', true)
                ->count();
            
            if ($totalLessons > 0 && $completedLessons === $totalLessons) {
                $certificates[] = [
                    'course' => $course,
                    'completed_at' => $client->courseProgress()
                        ->where('course_id', $course->id)
                        ->where('completed', true)
                        ->latest('completed_at')
                        ->first()
                        ->completed_at ?? now(),
                ];
            }
        }
        
        return view('client.training.certificates', compact('certificates'));
    }

    /**
     * Show downloads
     */
    public function downloads()
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->hasCourseAccess()) {
            return redirect()->route('client.financial.index')
                ->with('error', 'Você precisa de uma fatura ativa para acessar os cursos.');
        }
        
        // Get all attachments from completed lessons
        $downloads = [];
        
        $completedLessons = $client->courseProgress()
            ->where('completed', true)
            ->with(['lesson.attachments'])
            ->get();
        
        foreach ($completedLessons as $progress) {
            if ($progress->lesson && $progress->lesson->attachments) {
                foreach ($progress->lesson->attachments as $attachment) {
                    $downloads[] = [
                        'attachment' => $attachment,
                        'course' => $progress->course,
                        'lesson' => $progress->lesson,
                    ];
                }
            }
        }
        
        return view('client.training.downloads', compact('downloads'));
    }

    /**
     * Calculate overall progress
     */
    private function calculateOverallProgress($client, $courses)
    {
        $totalLessons = 0;
        $completedLessons = 0;
        
        foreach ($courses as $course) {
            $courseLessons = $course->lessons->count();
            $totalLessons += $courseLessons;
            
            $completedLessons += $client->courseProgress()
                ->where('course_id', $course->id)
                ->where('completed', true)
                ->count();
        }
        
        return $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
    }
}
