<?php
declare(strict_types = 1);


namespace Patrikap\Apiator\Contracts;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Interface JsonLoggerInterface
 * @package Patrikap\Apiator\Contracts
 *
 * Interface to logging request/response
 *
 * @author Konstantin.K
 */
interface JsonLoggerInterface
{
    /**
     * Логгирование реквеста
     *
     * @param Request $request
     */
    public function logRequest(Request $request): self;

    /**
     * Логгирование респонса
     *
     * @param JsonResponse $response
     */
    public function logResponse(JsonResponse $response): self;
}
