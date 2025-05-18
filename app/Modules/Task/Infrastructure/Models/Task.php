<?php

namespace App\Modules\Task\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Project\Infrastructure\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'status_id',
        'assignee_id',
    ];

    public static function newFactory()
    {
        return \Database\Factories\TaskFactory::new();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function status() {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
