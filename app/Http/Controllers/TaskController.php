<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\DeleteCompletedTask;
use App\Models\Task;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Cache::remember('tasks.index', 60, function () {
            return Task::orderByDesc('created_at')->get();
        });
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_limite' => ['nullable', 'date'],
        ]);
        $task = Task::create($data);
        Cache::forget('tasks.index');
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = Cache::remember("tasks.show.$id", 60, function () use ($id) {
            return Task::findOrFail($id);
        });
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $data = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_limite' => ['nullable', 'date'],
        ]);
        $task->update($data);
        Cache::forget('tasks.index');
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();
        Cache::forget('tasks.index');
        return response()->json(['message' => 'Task soft deleted.']);
    }

    /**
     * Toggle the completion status of a task and dispatch job if completed.
     */
    public function toggle(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->finalizado = !$task->finalizado;
        $task->save();
        Cache::forget('tasks.index');
        if ($task->finalizado) {
            DeleteCompletedTask::dispatch($task->id)->delay(now()->addMinutes(10));
        }
        return response()->json($task);
    }
}
