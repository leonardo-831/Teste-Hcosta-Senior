<?php

namespace App\Modules\Task\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Task\Application\Resources\TaskResource;
use App\Modules\Task\Application\Services\TaskService;
use App\Modules\Task\Interfaces\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index($projectId)
    {
        $tasks = $this->service->list((int)$projectId);
        return $tasks
            ? TaskResource::collection(collect($tasks))
            : response()->json(['error' => 'Nenhuma tarefa encontrada!'], 404);
    }

    public function store(TaskRequest $request, $projectId)
    {
        try {
            $task = $this->service->create($request->validated(), (int)$projectId, auth()->id());
            return response()->json(new TaskResource($task), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function show($id)
    {
        $task = $this->service->show((int)$id);
        return $task
            ? new TaskResource($task)
            : response()->json(['error' => 'Tarefa não encontrada!'], 404);
    }

    public function update(TaskRequest $request, $id)
    {
        try {
            $task = $this->service->update((int)$id, $request->validated(), auth()->id());
            return new TaskResource($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete((int)$id, auth()->id());
            return response()->json(['message' => 'Tarefa deletada!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
