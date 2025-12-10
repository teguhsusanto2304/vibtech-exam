<?php

namespace App\Http\Controllers;

use App\Models\UserExam;
use App\Models\User;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamAssignmentController extends Controller
{
    /**
     * Assign exam to user with deadline
     */
    public function assignExamToUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
            'days_to_complete' => 'required|integer|min:1|max:90', // 1-90 hari
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Check if user is admin
        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot assign exam to admin users.'
            ], 422);
        }

        $exam = Exam::findOrFail($validated['exam_id']);

        // Check if user already has this exam pending
        $existingExam = UserExam::where('user_id', $validated['user_id'])
            ->where('exam_id', $validated['exam_id'])
            ->where('data_status', 'pending')
            ->first();

        if ($existingExam) {
            return response()->json([
                'success' => false,
                'message' => 'User already has this exam assigned (pending).'
            ], 422);
        }

        // Create exam assignment with deadline
        $scheduledDeadline = now()->addDays($validated['days_to_complete']);

        $userExam = UserExam::create([
            'user_id' => $validated['user_id'],
            'exam_id' => $validated['exam_id'],
            'data_status' => 'pending',
            'scheduled_deadline' => $scheduledDeadline,
            'notification_sent' => false,
            'attempts_used' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user_exam_id' => $userExam->id,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'exam' => [
                    'id' => $exam->id,
                    'title' => $exam->title,
                ],
                'deadline' => $scheduledDeadline->format('Y-m-d H:i:s'),
                'days_to_complete' => $validated['days_to_complete'],
            ],
            'message' => "Exam assigned to {$user->name} with deadline in {$validated['days_to_complete']} days."
        ]);
    }

    /**
     * Get all pending exams for user with deadline info
     */
    public function getUserPendingExams($userId)
    {
        $user = User::findOrFail($userId);

        $pendingExams = $user->userExams()
            ->with('exam')
            ->where('data_status', 'pending')
            ->get()
            ->map(function ($userExam) {
                $daysRemaining = $userExam->scheduled_deadline 
                    ? now()->diffInDays($userExam->scheduled_deadline, false)
                    : null;

                return [
                    'user_exam_id' => $userExam->id,
                    'exam' => [
                        'id' => $userExam->exam->id,
                        'title' => $userExam->exam->title,
                        'questions' => $userExam->exam->questions,
                    ],
                    'status' => $userExam->data_status,
                    'attempts_used' => $userExam->attempts_used,
                    'scheduled_deadline' => $userExam->scheduled_deadline?->format('Y-m-d H:i:s'),
                    'days_remaining' => $daysRemaining,
                    'is_overdue' => isExamOverdue($userExam),
                    'is_approaching_deadline' => isApproachingDeadline($userExam, 3),
                ];
            });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'pending_exams' => $pendingExams,
            'total_pending' => $pendingExams->count(),
        ]);
    }

    /**
     * Get admin dashboard - all pending exams
     */
    public function adminDashboard()
    {
        // Semua pending exams
        $allPendingExams = UserExam::with(['user', 'exam'])
            ->where('data_status', 'pending')
            ->latest()
            ->get();

        // Overdue exams
        $overdueExams = $allPendingExams->filter(function ($exam) {
            return isExamOverdue($exam);
        })->values();

        // Approaching deadline (3 days)
        $approachingDeadline = $allPendingExams->filter(function ($exam) {
            return isApproachingDeadline($exam, 3);
        })->values();

        // Format data
        $formattedData = [
            'total_pending' => $allPendingExams->count(),
            'total_overdue' => $overdueExams->count(),
            'total_approaching_deadline' => $approachingDeadline->count(),
            'pending_exams' => $allPendingExams->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'user' => [
                        'id' => $exam->user->id,
                        'name' => $exam->user->name,
                        'email' => $exam->user->email,
                    ],
                    'exam' => [
                        'id' => $exam->exam->id,
                        'title' => $exam->exam->title,
                    ],
                    'assigned_at' => $exam->created_at->format('Y-m-d H:i:s'),
                    'deadline' => $exam->scheduled_deadline?->format('Y-m-d H:i:s'),
                    'days_remaining' => daysRemaining($exam),
                    'status' => isExamOverdue($exam) ? 'overdue' : 'pending',
                    'attempts_used' => $exam->attempts_used,
                ];
            })->toArray(),
            'overdue_exams' => $overdueExams->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'user_name' => $exam->user->name,
                    'exam_title' => $exam->exam->title,
                    'deadline' => $exam->scheduled_deadline->format('Y-m-d H:i:s'),
                    'days_overdue' => now()->diffInDays($exam->scheduled_deadline),
                ];
            })->toArray(),
            'approaching_deadline' => $approachingDeadline->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'user_name' => $exam->user->name,
                    'exam_title' => $exam->exam->title,
                    'deadline' => $exam->scheduled_deadline->format('Y-m-d H:i:s'),
                    'days_remaining' => daysRemaining($exam),
                ];
            })->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $formattedData
        ]);
    }

    /**
     * Reset notification_sent flag to resend reminders
     */
    public function resetNotificationFlag($userExamId)
    {
        $userExam = UserExam::findOrFail($userExamId);

        $userExam->notification_sent = false;
        $userExam->save();

        return response()->json([
            'success' => true,
            'message' => "Notification flag reset for {$userExam->user->name}. Reminders will be sent again.",
        ]);
    }

    /**
     * Extend exam deadline
     */
    public function extendDeadline(Request $request, $userExamId)
    {
        $validated = $request->validate([
            'additional_days' => 'required|integer|min:1|max:30',
        ]);

        $userExam = UserExam::findOrFail($userExamId);

        if ($userExam->data_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Can only extend deadline for pending exams.'
            ], 422);
        }

        $oldDeadline = $userExam->scheduled_deadline;
        $newDeadline = $userExam->scheduled_deadline->addDays($validated['additional_days']);

        $userExam->scheduled_deadline = $newDeadline;
        $userExam->notification_sent = false; // Reset untuk send reminder baru
        $userExam->save();

        return response()->json([
            'success' => true,
            'data' => [
                'user_exam_id' => $userExam->id,
                'user_name' => $userExam->user->name,
                'exam_title' => $userExam->exam->title,
                'old_deadline' => $oldDeadline->format('Y-m-d H:i:s'),
                'new_deadline' => $newDeadline->format('Y-m-d H:i:s'),
                'additional_days' => $validated['additional_days'],
            ],
            'message' => "Deadline extended by {$validated['additional_days']} days."
        ]);
    }

    /**
     * Cancel exam assignment
     */
    public function cancelAssignment($userExamId, Request $request)
    {
        $userExam = UserExam::findOrFail($userExamId);

        $reason = $request->input('reason', 'Admin cancelled the assignment');

        $userExam->data_status = 'cancel';
        $userExam->notes = $reason;
        $userExam->save();

        return response()->json([
            'success' => true,
            'message' => "Exam assignment cancelled for {$userExam->user->name}.",
            'data' => [
                'user_exam_id' => $userExam->id,
                'new_status' => $userExam->data_status,
                'reason' => $reason,
            ]
        ]);
    }
}
