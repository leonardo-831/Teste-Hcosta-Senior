<?php

namespace App\Modules\Project\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Project\Application\Resources\ProjectResource;
use Illuminate\Http\Request;
use App\Modules\Project\Application\Services\ProjectService;
use App\Modules\Project\Interfaces\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function index()
    {
        $projects = $this->service->list(auth()->id());
        return $projects
            ? ProjectResource::collection(collect($projects))
            : response()->json(['error' => 'Nenhum projeto encontrado!'], 404);
    }

    public function store(ProjectRequest $request)
    {
        try {
            $project = $this->service->create($request->validated(), auth()->id());
            return new ProjectResource($project);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function show($id)
    {
        $project = $this->service->show((int) $id);
        return $project
            ? new ProjectResource($project)
            : response()->json(['error' => 'Projeto não encontrado!'], 404);
    }

    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = $this->service->update((int)$id, $request->validated(), auth()->id());
            return new ProjectResource($project);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete((int)$id, auth()->id());
            return response()->json(['message' => 'Projeto Deletado!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
