<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\TodoResource;
use App\Services\TodoService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function __construct(
        private TodoService $todoService
    )
    {
    }

    public function index(Request $request): TodoCollection
    {
        $filters = [
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'search' => $request->get('search'),
            'due_date_from' => $request->get('due_date_from'),
            'due_date_to' => $request->get('due_date_to'),
            'per_page' => $request->get('per_page', 15)
        ];

        $todos = $this->todoService->getAllTodos($request->user(), $filters);

        return new TodoCollection($todos);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id, Request $request): TodoResource
    {
        $todo = $this->todoService->getTodoById($id, $request->user());

        return new TodoResource($todo);
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $this->todoService->createTodo(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Todo создан успешно',
            'data' => new TodoResource($todo)
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        $todo = $this->todoService->updateTodo(
            $id,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Todo обновлен успешно',
            'data' => new TodoResource($todo)
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        $this->todoService->deleteTodo($id, $request->user());

        return response()->json([
            'message' => 'Todo удален успешно'
        ]);
    }

    public function complete(int $id, Request $request): JsonResponse
    {
        $todo = $this->todoService->completeTodo($id, $request->user());

        return response()->json([
            'message' => 'Todo отмечен как выполненный',
            'data' => new TodoResource($todo)
        ]);
    }

    public function archive(int $id, Request $request): JsonResponse
    {
        $todo = $this->todoService->archiveTodo($id, $request->user());

        return response()->json([
            'message' => 'Todo архивирован',
            'data' => new TodoResource($todo)
        ]);
    }

    public function byStatus(Request $request, string $status): JsonResponse
    {
        $todos = $this->todoService->getTodosByStatus($request->user(), $status);

        return response()->json([
            'data' => TodoResource::collection($todos)
        ]);
    }

    public function byPriority(Request $request, string $priority): JsonResponse
    {
        $todos = $this->todoService->getTodosByPriority($request->user(), $priority);

        return response()->json([
            'data' => TodoResource::collection($todos)
        ]);
    }

    public function assigned(Request $request): JsonResponse
    {
        $todos = $this->todoService->getAssignedTodos($request->user());

        return response()->json([
            'data' => TodoResource::collection($todos)
        ]);
    }

    public function created(Request $request): JsonResponse
    {
        $todos = $this->todoService->getCreatedTodos($request->user());

        return response()->json([
            'data' => TodoResource::collection($todos)
        ]);
    }
}
