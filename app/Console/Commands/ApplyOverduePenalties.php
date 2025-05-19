<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskActivityLog;
use Carbon\Carbon;

class ApplyOverduePenalties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:apply-overdue-penalties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply point penalties for overdue tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        // Get all overdue tasks that haven't had penalties applied yet
        $overdueTasks = Task::whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->whereNull('completed_at')
            ->where(function($query) {
                $query->where('overdue_penalty_applied', false)
                      ->orWhereNull('overdue_penalty_applied');
            })
            ->whereNotNull('assignee_id')
            ->get();

        if ($overdueTasks->isEmpty()) {
            $this->info('No new overdue tasks found requiring penalties.');
            return 0;
        }

        $this->info("Found {$overdueTasks->count()} overdue tasks to process.");

        $totalPenalties = 0;
        $affectedUsers = [];

        foreach ($overdueTasks as $task) {
            $penalty = $task->applyOverduePenalty();

            if ($penalty > 0) {
                $totalPenalties += $penalty;
                $userId = $task->assignee_id;

                if (!isset($affectedUsers[$userId])) {
                    $affectedUsers[$userId] = [
                        'name' => $task->assignee->name,
                        'count' => 0,
                        'points' => 0
                    ];
                }

                $affectedUsers[$userId]['count']++;
                $affectedUsers[$userId]['points'] += $penalty;

                $this->comment("Applied {$penalty} point penalty for task: {$task->title}");
            }
        }

        $this->info("Applied a total of {$totalPenalties} penalty points.");

        if (!empty($affectedUsers)) {
            $this->info("\nAffected users:");
            foreach ($affectedUsers as $userId => $data) {
                $this->line("- {$data['name']}: {$data['count']} tasks, -{$data['points']} points");
            }
        }

        return 0;
    }
}
