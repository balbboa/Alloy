<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DeleteCompletedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $taskId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $task = Task::withTrashed()->find($this->taskId);

            if (!$task) {
                Log::info("Task {$this->taskId} not found for deletion");
                return;
            }

            // Check if task is still completed
            if (!$task->finalizado) {
                Log::info("Task {$this->taskId} is no longer completed, skipping deletion");
                return;
            }

            // Permanently delete the task
            $task->forceDelete();

            // Clear cache
            Cache::tags(['tasks'])->flush();

            Log::info("Task {$this->taskId} permanently deleted after completion");
        } catch (\Exception $e) {
            Log::error("Error deleting completed task {$this->taskId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to delete completed task {$this->taskId}: " . $exception->getMessage());
    }
}
