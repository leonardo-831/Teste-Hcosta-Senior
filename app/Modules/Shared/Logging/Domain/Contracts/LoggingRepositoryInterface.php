<?php

namespace App\Modules\Shared\Logging\Domain\Contracts;

interface LoggingRepositoryInterface
{
    /**
     * Registra um log com os dados fornecidos.
     *
     * @param array $data Dados do log, ex: action, model, model_id, payload, timestamp, etc.
     */
    public function log(array $data): void;
}
