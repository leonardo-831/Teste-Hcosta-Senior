<?php

namespace App\Modules\Project\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Task\Infrastructure\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'owner_id'];

    public static function newFactory()
    {
        return \Database\Factories\ProjectFactory::new();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
