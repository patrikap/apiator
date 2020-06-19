<?php
declare(strict_types = 1);

namespace Patrikap\Apiator\Contracts;

use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Interface JsonResponseFormatterInterface
 * @package Patrikap\Apiator\Contracts
 *
 * Interface to formatting response
 *
 * @author Konstantin.K
 */
interface JsonResponseFormatterInterface
{
    /**
     * Возвращает форматированное исключение
     *
     * @param Throwable $exception
     * @param int $statusCode
     * @return $this
     */
    public function formatResponseWithException(Throwable $exception, int $statusCode): self;

    /**
     * Метод преобразования ответа к единому формату
     *
     * @param JsonResponse $response
     * @return self
     */
    public function formatResponse(JsonResponse $response): self;

    /**
     * Возвращает форматированный массив
     *
     * @return array
     */
    public function getJson(): array;

    /**
     * Устанавливает доп поле в информационном подмассиве
     *
     * @param mixed $key
     * @param mixed $val
     * @return $this
     */
    public function setAdditionalInfo($key, $val): self;
}
