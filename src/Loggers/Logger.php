<?php
declare(strict_types = 1);


namespace Patrikap\Apiator\Loggers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Patrikap\Apiator\Contracts\JsonLoggerInterface;

/**
 * Class Logger
 * @package Patrikap\Apiator\Loggers
 *
 * Dummy logger to API request/response
 *
 * @author Konstantin.K
 */
class Logger implements JsonLoggerInterface
{
    /** @inheritDoc */
    public function logRequest(Request $request): self
    {
        return $this;
    }

    /** @inheritDoc */
    public function logResponse(JsonResponse $response): self
    {
        return $this;
    }
}
