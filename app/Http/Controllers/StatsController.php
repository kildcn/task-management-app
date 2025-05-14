<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStat;
use App\Models\Task;
use App\Models\Category;
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

        return view('stats.index', compact(
            'householdStats',
            'maxCreated',
            'maxCompleted',
            'categoryStats'
        ));
    }
}
