<?php

namespace App\Modules\Task\Tests\Unit;

use App\Modules\Shared\Authorization\PermissionService;
use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Task\Application\Services\TaskService;
use App\Modules\Task\Domain\Entities\Task;
use App\Modules\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Modules\Task\Domain\ValueObjects\TaskStatus;

# php artisan test --filter=TaskServiceTest
class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $service;
    protected $permissionService;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(TaskRepositoryInterface::class);
        $this->permissionService = Mockery::mock(PermissionService::class);
        $this->service = new TaskService($this->repository, $this->permissionService);
    }

    # php artisan test --filter=TaskServiceTest::testCreateTaskSuccessfully
    public function testCreateTaskSuccessfully(): void
    {
        $data = [
            'name' => 'My Task',
            'description' => 'Some text',
            'statusId' => 1,
            'assigneeId' => 1
        ];

        $this->permissionService->shouldReceive('canCreateTask')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $this->repository->shouldReceive('save')->once()->andReturnUsing(function (Task $task) {
            $this->assertEquals('My Task', $task->name);
            $this->assertEquals('Some text', $task->description);
            $this->assertEquals(1, $task->status->id());
            $this->assertEquals(1, $task->assigneeId);
            return $task;
        });

        $this->mock('auth')->shouldReceive('user')->andReturn((object)['name' => 'Test User', 'email' => 'teste@gmail.com']);

        $task = $this->service->create($data, 1,  1);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('My Task', $task->name);
    }

    # php artisan test --filter=TaskServiceTest::testUpdateTaskSuccessfully
    public function testUpdateTaskSuccessfully(): void
    {
        $original = new Task(
            id: 1,
            projectId: 1,
            name: 'Old',
            description: 'Old desc',
            assigneeId: 1,
            status: new TaskStatus(1)
        );

        $this->permissionService->shouldReceive('canUpdateTask')
            ->once()
            ->with(1, 1)
            ->andReturn(true);

        $this->repository->shouldReceive('findById')->once()->with(1)->andReturn($original);
        $this->repository->shouldReceive('save')->once()->andReturnUsing(function (Task $task) {
            $this->assertEquals(1, $task->projectId);
            $this->assertEquals('New Name', $task->name);
            $this->assertEquals('New Desc', $task->description);
            $this->assertEquals(2, $task->assigneeId);
            $this->assertEquals(2, $task->status->id());
            return $task;
        });

        $this->mock('auth')->shouldReceive('user')->andReturn((object)['name' => 'Test User']);

        $updated = $this->service->update(1, [
            'name' => 'New Name',
            'description' => 'New Desc',
            'statusId' => 2,
            'assigneeId' => 2
        ], 1);

        $this->assertEquals('New Name', $updated->name);
    }

    # php artisan test --filter=TaskServiceTest::testUpdateReturnsNullWhenTaskNotFound
    public function testUpdateReturnsNullWhenTaskNotFound(): void
    {
        $this->permissionService->shouldReceive('canUpdateTask')
            ->once()
            ->with(1, 999)
            ->andReturn(true);

        $this->repository->shouldReceive('findById')->once()->with(999)->andReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Tarefa não encontrada!");

        $result = $this->service->update(999, [
            'name' => 'Does not matter',
            'description' => 'Irrelevant'
        ], 1);
    }

    # php artisan test --filter=ProjectServiceTest::testDeleteCallsRepository
    public function testDeleteCallsRepository(): void
    {
        $this->repository->shouldReceive('delete')
            ->once()
            ->with(5);

        $this->permissionService->shouldReceive('canDeleteTask')
            ->once()
            ->with(1, 5)
            ->andReturn(true);

        $this->service->delete(5, 1);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
