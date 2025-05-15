<?php

namespace App\Modules\Task\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Task\Application\Services\TaskService;
use App\Modules\Task\Interfaces\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index($projectId)
    {
        return response()->json($this->service->list((int)$projectId));
    }

    public function store(TaskRequest $request, $projectId)
    {
        $task = $this->service->create($request->validated(), (int)$projectId, auth()->id());
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = $this->service->show((int)$id);
        return $task
            ? response()->json($task)
            : response()->json(['error' => 'Not found'], 404);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->service->update((int)$id, $request->validated(), auth()->id());
        return response()->json($task);
    }

    public function destroy($id)
    {
        $this->service->delete((int)$id);
        return response()->json(['message' => 'Deleted']);
    }
}
