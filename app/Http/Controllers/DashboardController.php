<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskStat;
use App\Models\TaskActivityLog;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Handle case where user doesn't have a household yet
        if (!$user->hasHousehold()) {
            return redirect()->route('household.create')
                ->with('info', 'Please create or join a household to continue.');
        }

        $household = $user->household;

        // Get user's current tasks
        $myTasks = Task::where('assignee_id', $user->id)
            ->where('completed_at', null)
            ->with(['category', 'creator'])
            ->orderBy('urgency_score', 'desc')
            ->take(5)
            ->get();

        // Get overdue tasks for the household
        $overdueTasks = Task::where('household_id', $household->id)
            ->where('due_date', '<', now())
            ->where('completed_at', null)
            ->with(['assignee', 'category'])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Get all household tasks for calendar and priority distribution
        $allTasks = Task::where('household_id', $household->id)
            ->with(['category', 'assignee'])
            ->get();

        // Get recent activity
        $recentActivity = TaskActivityLog::whereHas('task', function($query) use ($household) {
                $query->where('household_id', $household->id);
            })
            ->with(['task', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get household stats and update them
        $householdStats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->get();

        // Update stats for all household members
        foreach ($householdStats as $stat) {
            $stat->updateStats();
        }

        // Re-fetch to get updated stats
        $householdStats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->orderBy('tasks_created_count', 'desc')
            ->get();

        // Calculate weekly progress more comprehensively
        $weekStart = Carbon::now()->startOfWeek();

        // Tasks assigned to user that were already available at start of week or created during this week
        $availableTasksThisWeek = Task::where('assignee_id', $user->id)
            ->where(function($query) use ($weekStart) {
                // Tasks created before this week that weren't completed
                $query->where(function($q) use ($weekStart) {
                    $q->where('created_at', '<', $weekStart)
                      ->where(function($q2) use ($weekStart) {
                          $q2->whereNull('completed_at')
                             ->orWhere('completed_at', '>=', $weekStart);
                      });
                })
                // OR tasks created this week
                ->orWhere('created_at', '>=', $weekStart);
            })
            ->count();

        // Tasks completed by user this week
        $completedThisWeek = Task::where('completed_by_id', $user->id)
            ->where('completed_at', '>=', $weekStart)
            ->count();

        // Weekly progress as a percentage of completed vs available tasks
        // Capped at 100% to avoid confusion
        $weeklyProgress = $availableTasksThisWeek > 0 ?
            min(100, round(($completedThisWeek / $availableTasksThisWeek) * 100)) : 0;

        // Create objects to pass to the view
        $weeklyData = [
            'available' => $availableTasksThisWeek,
            'completed' => $completedThisWeek,
            'progress' => $weeklyProgress,
            'week_start' => $weekStart->format('M j'),
            'week_end' => $weekStart->copy()->endOfWeek()->format('M j'),
            // Include raw completion percentage for context
            'raw_percentage' => $availableTasksThisWeek > 0 ?
                round(($completedThisWeek / $availableTasksThisWeek) * 100) : 0
        ];

        return view('dashboard', compact(
            'myTasks',
            'overdueTasks',
            'allTasks',
            'recentActivity',
            'householdStats',
            'weeklyData'
        ));
    }
}
