<?php

namespace App\Modules\Task\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'status',
        'assignee_id',
        'creator_id',
    ];
}
