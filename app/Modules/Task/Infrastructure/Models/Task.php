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
        'status',
        'assignee_id',
        'creator_id',
    ];

    public static function newFactory()
    {
        return \Database\Factories\TaskFactory::new();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
