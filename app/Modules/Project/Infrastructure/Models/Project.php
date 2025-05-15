<?php

namespace App\Modules\Project\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'owner_id'];
}