<?php

namespace App\Modules\Project\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Project\Application\Services\ProjectService;
use App\Modules\Project\Interfaces\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function index()
    {
        $projects = $this->service->list(auth()->id());
        return response()->json($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->service->create($request->name, auth()->id());
        return response()->json($project, 201);
    }

    public function show($id)
    {
        $project = $this->service->show((int)$id);
        return $project
            ? response()->json($project)
            : response()->json(['error' => 'Not found'], 404);
    }

    public function update(ProjectRequest $request, $id)
    {
        $project = $this->service->update((int)$id, $request->name, auth()->id());
        return response()->json($project);
    }

    public function destroy($id)
    {
        $this->service->delete((int)$id);
        return response()->json(['message' => 'Deleted']);
    }
}
