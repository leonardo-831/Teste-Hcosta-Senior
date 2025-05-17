<?php

namespace App\Modules\Shared\Logging\Infrastructure\Models;

use MongoDB\Laravel\Eloquent\Model;

class LogMongoModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'action_logs';

    protected $fillable = [
        'user_id',
        'ip',
        'route',
        'method',
        'action',
        'model',
        'model_id',
        'payload',
        'timestamp',
    ];

    protected $dates = [
        'timestamp',
    ];
}
