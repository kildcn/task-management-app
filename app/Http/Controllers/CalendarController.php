<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar view
     */
    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $household = Auth::user()->household;

        // Get current month start and end dates
        $firstDay = $date->copy()->startOfMonth();
        $lastDay = $date->copy()->endOfMonth();

        // Get previous and next month navigation
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        // Get tasks for this month
        $tasks = Task::where('household_id', $household->id)
            ->where(function($query) use ($firstDay, $lastDay) {
                $query->whereBetween('due_date', [$firstDay, $lastDay])
                      ->orWhereBetween('created_at', [$firstDay, $lastDay])
                      ->orWhereBetween('completed_at', [$firstDay, $lastDay]);
            })
            ->with(['category', 'assignee', 'creator', 'completedBy'])
            ->orderBy('due_date')
            ->get();

        // Organize tasks by date for the calendar
        $tasksByDate = [];
        foreach ($tasks as $task) {
            $date = $task->due_date ?? $task->created_at->startOfDay();
            $dateKey = $date->format('Y-m-d');

            if (!isset($tasksByDate[$dateKey])) {
                $tasksByDate[$dateKey] = [];
            }

            $tasksByDate[$dateKey][] = $task;
        }

        // Get all category colors for styling
        $categories = Category::where('household_id', $household->id)->get();

        return view('calendar.index', compact(
            'date',
            'firstDay',
            'lastDay',
            'prevMonth',
            'nextMonth',
            'tasks',
            'tasksByDate',
            'categories'
        ));
    }

    /**
     * Get data for a specific month
     */
    public function getMonthData($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1);
        $household = Auth::user()->household;

        // Get current month start and end dates
        $firstDay = $date->copy()->startOfMonth();
        $lastDay = $date->copy()->endOfMonth();

        // Get tasks for this month
        $tasks = Task::where('household_id', $household->id)
            ->whereBetween('due_date', [$firstDay, $lastDay])
            ->with(['category', 'assignee'])
            ->orderBy('due_date')
            ->get();

        // Group tasks by date
        $tasksByDate = [];
        foreach ($tasks as $task) {
            $dateKey = $task->due_date->format('Y-m-d');

            if (!isset($tasksByDate[$dateKey])) {
                $tasksByDate[$dateKey] = [];
            }

            $tasksByDate[$dateKey][] = [
                'id' => $task->id,
                'title' => $task->title,
                'priority' => $task->priority,
                'completed' => !is_null($task->completed_at),
                'assignee' => $task->assignee ? $task->assignee->name : 'Unassigned',
                'category' => [
                    'name' => $task->category->name,
                    'color' => $task->category->color,
                ]
            ];
        }

        return response()->json([
            'year' => (int)$year,
            'month' => (int)$month,
            'days' => $date->daysInMonth,
            'firstDayOfWeek' => $firstDay->dayOfWeek,
            'tasks' => $tasksByDate,
            'monthName' => $date->format('F Y')
        ]);
    }

    /**
     * Get detailed task information for a specific day
     */
    public function getDayDetails($date)
    {
        $date = Carbon::parse($date);
        $household = Auth::user()->household;

        // Get all tasks due on this date
        $tasks = Task::where('household_id', $household->id)
            ->whereDate('due_date', $date)
            ->with(['category', 'assignee', 'creator'])
            ->orderBy('priority')
            ->get();

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'formattedDate' => $date->format('l, F j, Y'),
            'tasks' => $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'priority' => $task->priority,
                    'completed' => !is_null($task->completed_at),
                    'completed_at' => $task->completed_at ? $task->completed_at->format('M j, g:i A') : null,
                    'completed_by' => $task->completedBy ? $task->completedBy->name : null,
                    'assignee' => $task->assignee ? $task->assignee->name : 'Unassigned',
                    'creator' => $task->creator->name,
                    'description' => $task->description,
                    'category' => [
                        'name' => $task->category->name,
                        'color' => $task->category->color,
                        'icon' => $task->category->icon,
                    ],
                    'url' => route('tasks.show', $task)
                ];
            })
        ]);
    }
}
