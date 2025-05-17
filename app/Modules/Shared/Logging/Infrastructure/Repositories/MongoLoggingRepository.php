<?php

namespace App\Modules\Shared\Logging\Infrastructure\Repositories;

use App\Modules\Shared\Logging\Domain\Contracts\LoggingRepositoryInterface;
use App\Modules\Shared\Logging\Infrastructure\Models\LogMongo;

class MongoLoggingRepository implements LoggingRepositoryInterface
{
    public function log(array $data): void
    {
        LogMongo::create($data);
    }
}
