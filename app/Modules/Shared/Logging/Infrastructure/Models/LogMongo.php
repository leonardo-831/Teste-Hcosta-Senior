<?php

namespace App\Modules\Shared\Logging\Infrastructure\Models;

use MongoDB\Laravel\Eloquent\Model;

class LogMongo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'action_logs';

    protected $fillable = [
        'usuario',
        'acao',
        'ip',
        'rota',
        'payload',
    ];

    protected $dates = [
        'timestamp',
    ];
}
