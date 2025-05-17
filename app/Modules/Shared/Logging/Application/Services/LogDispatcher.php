<?php

namespace App\Modules\Shared\Logging\Application\Services;

use App\Modules\Shared\Logging\Domain\Contracts\LoggingRepositoryInterface;
use Illuminate\Support\Facades\App;

class LogDispatcher
{
    protected static ?LoggingRepositoryInterface $repository = null;

    protected static function getRepository(): LoggingRepositoryInterface
    {
        if (!self::$repository) {
            self::$repository = App::make(LoggingRepositoryInterface::class);
        }
        return self::$repository;
    }

    public static function log(string $action, string $model, $modelId, array $payload = []): void
    {
        self::getRepository()->log([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'payload' => $payload
        ]);
    }
}
