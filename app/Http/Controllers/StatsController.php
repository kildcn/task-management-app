<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStat;
use App\Models\Task;
use App\Models\TaskActivityLog;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $household = $user->household;

        // Get all household stats
        $householdStats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->get();

        // Update all stats first
        foreach ($householdStats as $stat) {
            $stat->updateStats();
        }

        // Re-fetch updated stats
        $householdStats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->orderBy('points', 'desc')
            ->get();

        // Calculate max values for progress bars
        $maxCreated = $householdStats->max('tasks_created_count') ?: 1;
        $maxCompleted = $householdStats->max('tasks_completed_count') ?: 1;

        // Get category-wise statistics
        $categories = Category::where('household_id', $household->id)->get();
        $categoryStats = [];

        foreach ($categories as $category) {
            $categoryStats[$category->name] = [];

            foreach ($householdStats as $stat) {
                $createdCount = Task::where('category_id', $category->id)
                    ->where('creator_id', $stat->user_id)
                    ->count();

                $completedCount = Task::where('category_id', $category->id)
                    ->where('completed_by_id', $stat->user_id)
                    ->whereNotNull('completed_at')
                    ->count();

                $categoryStats[$category->name][] = [
                    'user' => $stat->user->name,
                    'created' => $createdCount,
                    'completed' => $completedCount,
                ];
            }
        }

        // Get recent task history (last 7 days)
        $recentTasks = $this->getRecentTaskHistory($household->id, 7);

        // Get monthly task summary
        $monthlyStats = $this->getMonthlyTaskStats($household->id);

        return view('stats.index', compact(
            'householdStats',
            'maxCreated',
            'maxCompleted',
            'categoryStats',
            'recentTasks',
            'monthlyStats'
        ));
    }

    /**
     * Get recent task history for specified number of days
     */
    private function getRecentTaskHistory($householdId, $days = 7)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // Get all tasks created or completed in the time period
        $createdTasks = Task::where('household_id', $householdId)
            ->where('created_at', '>=', $startDate)
            ->with(['creator', 'category'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'user' => $task->creator->name,
                    'category' => $task->category->name,
                    'date' => $task->created_at,
                    'type' => 'created',
                    'priority' => $task->priority,
                    'difficulty' => $task->difficulty
                ];
            });

        $completedTasks = Task::where('household_id', $householdId)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $startDate)
            ->with(['completedBy', 'category'])
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'user' => $task->completedBy->name,
                    'category' => $task->category->name,
                    'date' => $task->completed_at,
                    'type' => 'completed',
                    'priority' => $task->priority,
                    'difficulty' => $task->difficulty,
                    'points' => $task->completion_points
                ];
            });

        // Merge both collections and sort by date
        $allTasks = $createdTasks->concat($completedTasks)
            ->sortByDesc('date')
            ->values()
            ->all();

        return $allTasks;
    }

    /**
     * Get monthly task statistics
     */
    private function getMonthlyTaskStats($householdId)
    {
        // Past 6 months
        $months = [];
        $currentDate = Carbon::now();

        for ($i = 0; $i < 6; $i++) {
            $date = $currentDate->copy()->subMonths($i);
            $months[] = [
                'label' => $date->format('M Y'),
                'start_date' => $date->copy()->startOfMonth(),
                'end_date' => $date->copy()->endOfMonth()
            ];
        }

        $stats = [];

        foreach ($months as $month) {
            $created = Task::where('household_id', $householdId)
                ->whereBetween('created_at', [$month['start_date'], $month['end_date']])
                ->count();

            $completed = Task::where('household_id', $householdId)
                ->whereNotNull('completed_at')
                ->whereBetween('completed_at', [$month['start_date'], $month['end_date']])
                ->count();

            $stats[] = [
                'month' => $month['label'],
                'created' => $created,
                'completed' => $completed,
                'completion_rate' => $created > 0 ? round(($completed / $created) * 100, 1) : 0
            ];
        }

        return array_reverse($stats);
    }
}
