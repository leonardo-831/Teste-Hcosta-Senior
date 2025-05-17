<?php

namespace App\Modules\Shared\Logging\Infrastructure\Mongo;

use App\Modules\Shared\Logging\Domain\Contracts\LoggingRepositoryInterface;
use App\Modules\Shared\Logging\Infrastructure\Models\LogMongoModel;

class MongoLoggingRepository implements LoggingRepositoryInterface
{
    public function log(array $data): void
    {
        LogMongoModel::create($data);
    }
}
