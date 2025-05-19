<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use App\Models\TaskActivityLog;
use App\Models\TaskStat;
use App\Models\TaskReaction;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $household = $user->household;

        // Check for overdue tasks and apply penalties if needed
        $this->checkOverdueTasks($user);

        $tasks = Task::where('household_id', $household->id)
            ->with(['assignee', 'creator', 'category', 'completedBy'])
            ->orderBy('completed_at', 'asc')
            ->orderBy('urgency_score', 'desc')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        // Get categories and household members for filters
        $categories = Category::where('household_id', $household->id)
            ->orderBy('sort_order')
            ->get();

        return view('tasks.index', compact('tasks', 'categories', 'household'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $household = $user->household;

        $categories = Category::where('household_id', $household->id)
            ->orderBy('sort_order')
            ->get();

        $members = User::where('household_id', $household->id)
            ->where('is_active', true)
            ->get();

        // Get default due date from request query param
        $defaultDueDate = request('due_date');

        return view('tasks.create', compact('categories', 'members', 'defaultDueDate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'difficulty' => 'required|integer|min:1|max:5',
            'due_date' => 'nullable|date',
            'estimated_duration' => 'nullable|integer|min:1',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|in:daily,weekly,monthly',
        ]);

        $task = Task::create([
            'household_id' => $user->household_id,
            'creator_id' => $user->id,
            'assignee_id' => $validated['assignee_id'] ?? $user->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'difficulty' => $validated['difficulty'],
            'due_date' => $validated['due_date'],
            'estimated_duration' => $validated['estimated_duration'],
            'is_recurring' => $validated['is_recurring'] ?? false,
            'recurrence_pattern' => $validated['recurrence_pattern'] ?? null,
            'overdue_penalty_applied' => false,
        ]);

        // Log the activity
        TaskActivityLog::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'action' => 'created',
            'description' => "Created task: {$task->title}",
        ]);

        // Update creator's stats
        $creatorStats = TaskStat::firstOrCreate([
            'user_id' => $user->id,
            'household_id' => $user->household_id,
        ]);
        $creatorStats->updateStats();

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['assignee', 'creator', 'category', 'completedBy', 'activityLogs.user', 'reactions.user']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $user = Auth::user();
        $household = $user->household;

        $categories = Category::where('household_id', $household->id)
            ->orderBy('sort_order')
            ->get();

        $members = User::where('household_id', $household->id)
            ->where('is_active', true)
            ->get();

        return view('tasks.edit', compact('task', 'categories', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'difficulty' => 'required|integer|min:1|max:5',
            'due_date' => 'nullable|date',
            'estimated_duration' => 'nullable|integer|min:1',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|in:daily,weekly,monthly',
        ]);

        $changes = [];
        foreach ($validated as $key => $value) {
            if ($task->{$key} != $value) {
                $changes[$key] = ['old' => $task->{$key}, 'new' => $value];
            }
        }

        // Handle the case where recurrence_pattern might not be in the request
        $updateData = $validated;
        $updateData['recurrence_pattern'] = $validated['recurrence_pattern'] ?? null;

        // Reset overdue_penalty_applied if due date changes
        if (isset($changes['due_date'])) {
            $updateData['overdue_penalty_applied'] = false;
        }

        $task->update($updateData);

        if (!empty($changes)) {
            TaskActivityLog::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => "Updated task: {$task->title}",
                'changes' => $changes,
            ]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        TaskActivityLog::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => "Deleted task: {$task->title}",
        ]);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Mark task as completed
     */
    public function complete(Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'completed_at' => now(),
            'completed_by_id' => Auth::id(),
        ]);

        // Update user stats for both assignee and completor
        $this->updateUserStats($task->assignee);
        if ($task->assignee_id !== Auth::id()) {
            $this->updateUserStats(Auth::user());
        }

        TaskActivityLog::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'completed',
            'description' => "Completed task: {$task->title} (earned {$task->completion_points} points)",
        ]);

        return back()->with('success', "Task completed! You earned {$task->completion_points} points!");
    }

    /**
     * Reopen a completed task
     */
    public function reopen(Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'completed_at' => null,
            'completed_by_id' => null,
            'completion_notes' => null,
        ]);

        // Update user stats
        if ($task->assignee) {
            $this->updateUserStats($task->assignee);
        }

        TaskActivityLog::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'reopened',
            'description' => "Reopened task: {$task->title}",
        ]);

        return back()->with('success', 'Task reopened!');
    }

    /**
     * Add reaction to task
     */
    public function addReaction(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'type' => 'required|in:like,appreciate,well_done,helpful',
            'comment' => 'nullable|string|max:500',
        ]);

        TaskReaction::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
            ],
            [
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'Reaction added!');
    }

    /**
     * Check for overdue tasks and apply penalties
     */
    private function checkOverdueTasks(User $user)
    {
        // Find user's overdue tasks that need penalties
        $overdueTasks = Task::where('assignee_id', $user->id)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('completed_at')
            ->where(function($query) {
                $query->where('overdue_penalty_applied', false)
                      ->orWhereNull('overdue_penalty_applied');
            })
            ->where('household_id', $user->household_id)
            ->get();

        $totalPenalty = 0;
        $messages = [];

        foreach ($overdueTasks as $task) {
            $penalty = $task->applyOverduePenalty();
            if ($penalty > 0) {
                $totalPenalty += $penalty;
                $messages[] = "Task \"{$task->title}\" is overdue: -{$penalty} points";
            }
        }

        if ($totalPenalty > 0) {
            $messageText = "You've received a penalty of {$totalPenalty} points for overdue tasks!\n" . implode("\n", $messages);
            session()->flash('error', $messageText);
        }
    }

    /**
     * Update user stats when task is completed
     */
    private function updateUserStats(User $user)
    {
        $stats = TaskStat::firstOrCreate([
            'user_id' => $user->id,
            'household_id' => $user->household_id,
        ]);

        $stats->updateStats();
    }
}
