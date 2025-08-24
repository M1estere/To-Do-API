<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="To-Do API",
 *     description="API for task management"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get all tasks",
     *     @OA\Response(
     *         response=200,
     *         description="Task list"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Task::all());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="New task"),
     *             @OA\Property(property="description", type="string", example="New task desc"),
     *             @OA\Property(property="status", type="string", example="waiting")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Task already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task already exists")
     *         )
     *     )
     * )
     */
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $exists = Task::where('title', $validated['title'])
                    ->exists();

        if ($exists) {
            return response()->json(['message' => "Task `{$validated['title']}` already exists"], 409);
        }

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task found"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated task title"),
     *             @OA\Property(property="description", type="string", example="Updated task desc"),
     *             @OA\Property(property="status", type="string", example="completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Task already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task already exists")
     *         )
     *     )
     * )
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validated = $request->validated();

        // Проверка на дубликат, исключая текущую запись
        $exists = Task::where('title', $validated['title'] ?? $task->title)
                    ->exists();

        if ($exists) {
            return response()->json(['message' => "Task `{$validated['title']}` already exists"], 409);
        }

        $task->update($validated);

        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task deleted"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}
