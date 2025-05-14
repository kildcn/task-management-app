<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskStat;
use App\Models\TaskActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Handle case where user doesn't have a household yet
        if (!$user->household_id) {
            return redirect()->route('household.create');
        }

        $household = $user->household;

        // Get user's tasks
        $myTasks = Task::where('assignee_id', $user->id)
            ->where('completed_at', null)
            ->with(['category', 'creator'])
            ->orderBy('urgency_score', 'desc')
            ->take(5)
            ->get();

        // Get overdue tasks
        $overdueTasks = Task::where('household_id', $household->id)
            ->where('due_date', '<', now())
            ->where('completed_at', null)
            ->with(['assignee', 'category'])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Get recent activity
        $recentActivity = TaskActivityLog::whereHas('task', function($query) use ($household) {
                $query->where('household_id', $household->id);
            })
            ->with(['task', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get household stats
        $householdStats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->orderBy('points', 'desc')
            ->get();

        // Calculate completion rate for this week
        $weekStart = Carbon::now()->startOfWeek();
        $tasksThisWeek = Task::where('household_id', $household->id)
            ->where('created_at', '>=', $weekStart)
            ->count();
        $completedThisWeek = Task::where('household_id', $household->id)
            ->where('completed_at', '>=', $weekStart)
            ->count();
        $weeklyCompletionRate = $tasksThisWeek > 0 ? round(($completedThisWeek / $tasksThisWeek) * 100) : 0;

        return view('dashboard', compact(
            'myTasks',
            'overdueTasks',
            'recentActivity',
            'householdStats',
            'weeklyCompletionRate'
        ));
    }
}
