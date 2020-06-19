<?php
declare(strict_types = 1);


namespace Patrikap\Apiator\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Patrikap\Apiator\Services\ApiatorService;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ApiatorMiddleware
 * @package Patrikap\Apiator\Middleware
 *
 * Request / response middleware through the service
 *
 * @author Konstantin.K
 */
class ApiatorMiddleware
{
    /** @var ApiatorService apiator service */
    protected ApiatorService $apiator;

    /**
     * JsonMiddleware constructor.
     *
     * @param ApiatorService $apiator
     */
    public function __construct(ApiatorService $apiator)
    {
        $this->apiator = $apiator;
    }

    /**
     * Handle an incoming request
     * Forever send json response
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->apiator->start()->setRequest($request);
        // to always return json response
        $request->headers->set('Accept', 'application/json');
        /** @var Response|JsonResponse $response */
        $response = $next($request);
        if (!$response instanceof JsonResponse) {
            $response = response()->json(
                $response->content(),
                $response->status()
            );
        }

        return $this->apiator
            ->finish()
            ->setResponse($response)
            ->formatResponse();
    }

    /**
     * Логирует запрос и ответ, после того как отдаёт ответ клиенту
     * снижает время обработки
     *
     * @param Request $request
     * @param JsonResponse $response
     * @throws BindingResolutionException
     */
    public function terminate(Request $request, JsonResponse $response): void
    {
        $this->apiator->toLog();
    }
}
