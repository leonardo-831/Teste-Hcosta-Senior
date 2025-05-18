<?php

namespace App\Modules\Project\Tests\Unit;

use Tests\TestCase;
use App\Modules\Project\Application\Services\ProjectService;
use App\Modules\Project\Domain\Entities\Project;
use App\Modules\Project\Domain\Repositories\ProjectRepositoryInterface;
use App\Modules\Shared\Authorization\PermissionService;
use Mockery;

# php artisan test --filter=ProjectServiceTest
class ProjectServiceTest extends TestCase
{
    # php artisan test --filter=ProjectServiceTest::testCreateProjectSuccessfully
    public function testCreateProjectSuccessfully(): void
    {
        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $permissionService = Mockery::mock(PermissionService::class);

        $service = new ProjectService($repo, $permissionService);

        $data = [
            'name' => 'My Project',
            'description' => 'Some text'
        ];

        $repo->shouldReceive('save')->once()->andReturnUsing(function (Project $project) {
            $this->assertEquals('My Project', $project->name);
            $this->assertEquals('Some text', $project->description);
            $this->assertEquals(1, $project->ownerId);
            return $project;
        });

        $this->mock('auth')->shouldReceive('user')->andReturn((object)['name' => 'Test User']);

        $project = $service->create($data, 1);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('My Project', $project->name);
    }

    # php artisan test --filter=ProjectServiceTest::testUpdateProjectSuccessfully
    public function testUpdateProjectSuccessfully(): void
    {
        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $permissionService = Mockery::mock(PermissionService::class);

        $service = new ProjectService($repo, $permissionService);

        $original = new Project(id: 1, name: 'Old', description: 'Old desc', ownerId: 1);

        $permissionService->shouldReceive('canEditProject')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $repo->shouldReceive('findById')->once()->with(1)->andReturn($original);
        $repo->shouldReceive('save')->once()->andReturnUsing(function (Project $project) {
            $this->assertEquals('New Name', $project->name);
            $this->assertEquals('New Desc', $project->description);
            return $project;
        });

        $this->mock('auth')->shouldReceive('user')->andReturn((object)['name' => 'Test User']);

        $updated = $service->update(1, [
            'name' => 'New Name',
            'description' => 'New Desc'
        ], 1);

        $this->assertEquals('New Name', $updated->name);
    }

    # php artisan test --filter=ProjectServiceTest::testUpdateReturnsNullWhenProjectNotFound
    public function testUpdateReturnsNullWhenProjectNotFound(): void
    {
        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $permissionService = Mockery::mock(PermissionService::class);

        $service = new ProjectService($repo, $permissionService);

        $permissionService->shouldReceive('canEditProject')
            ->once()
            ->with(1, 999)
            ->andReturn(true);

        $repo->shouldReceive('findById')->once()->with(999)->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Projeto não encontrado!");

        $result = $service->update(999, [
            'name' => 'Does not matter',
            'description' => 'Irrelevant'
        ], 1);
    }

    # php artisan test --filter=ProjectServiceTest::testDeleteCallsRepository
    public function testDeleteCallsRepository(): void
    {
        $repo = Mockery::mock(ProjectRepositoryInterface::class);
        $permissionService = Mockery::mock(PermissionService::class);

        $service = new ProjectService($repo, $permissionService);

        $repo->shouldReceive('delete')
            ->once()
            ->with(5);

        $permissionService->shouldReceive('canDeleteProject')
            ->once()
            ->with(1, 5)
            ->andReturn(true);

        $service->delete(5, 1);
    }
}
