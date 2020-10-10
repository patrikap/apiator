<?php
declare(strict_types = 1);

namespace Patrikap\Apiator\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Patrikap\Apiator\Contracts\JsonLoggerInterface;
use Patrikap\Apiator\Contracts\JsonResponseFormatterInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ApiatorService
 * @package Patrikap\Apiator\Services
 *
 * Service for work with formatting and logging API request/response
 *
 * @author Konstantin.K
 */
class ApiatorService
{
    /** @var string|null уникальный идентификатор запроса */
    private ?string $requestId;
    /** @var float|null время начала обработки данных */
    private ?float $startTime;
    /** @var float|null время окончания обработки данных и передачи их клиенту */
    private ?float $finishTime;
    /** @var JsonResponseFormatterInterface реализация форматтера ответа */
    private JsonResponseFormatterInterface $formatter;
    /** @var JsonLoggerInterface|null реализация логгера */
    private ?JsonLoggerInterface $logger;
    /** @var Request|null запрос */
    private ?Request $request;
    /** @var JsonResponse|null ответ */
    private ?JsonResponse $response;
    /** @var string заголовок ответа для отражения идентификатора запроса */
    private string $requestIdHeaderName;
    /** @var string заголовок ответа для отражения времени выполнения кода */
    private string $runtimeHeaderName;

    /**
     * ApiatorService constructor.
     *
     * @param JsonResponseFormatterInterface $formatter
     */
    public function __construct(JsonResponseFormatterInterface $formatter)
    {
        $this->requestIdHeaderName = config('apiator.headers.requestId');
        $this->runtimeHeaderName = config('apiator.headers.runtime');
        $this->formatter = $formatter;
    }

    /**
     * Стартует логирование времени выполнения
     * + устанавливает уникальный ключ запроса
     *
     * @return $this
     */
    public function start(): self
    {
        $this->startTime = microtime(true);
        $this->requestId = uniqid(config('apiator.requetIdPrefix'), false);

        return $this;
    }

    /**
     * Устанавливает время отправки ответа клиенту
     *
     * @return $this
     */
    public function finish(): self
    {
        $this->finishTime = microtime(true);

        return $this;
    }

    /**
     * Записывает запрос в свойство
     *
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Записывает ответ в свойство
     *
     * @param JsonResponse $response
     * @return $this
     */
    public function setResponse(JsonResponse $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Форматирует ответ с помощью форматтера
     *
     * @return JsonResponse
     */
    public function formatResponse(): JsonResponse
    {
        $exception = $this->getException();
        if ($exception) {
            $formattedResponse = $this->formatter->formatResponseWithException($exception, $this->response->getStatusCode());
        } else {
            $formattedResponse = $this->formatter->formatResponse($this->response);
        }

        $this->response
            ->setData($formattedResponse->getJson())
            ->withHeaders($this->getResponseHeaders());

        return $this->response;
    }

    /**
     * Логирует запрос ответ
     *
     * @throws BindingResolutionException
     */
    public function toLog(): void
    {
        if (config('apiator.loggingEnabled')) {
            $this->logger = app()->make(JsonLoggerInterface::class);
            // установить форматтеры
            // возможно переместить и/или разделить класс логгера
            $this->logger->setFormatter()
                ->logRequest($this->request)
                ->logResponse($this->response);
        }

    }

    /***************************/
    /**
     * Получение заголовков ответа
     *
     * @return array
     */
    protected function getResponseHeaders(): array
    {
        return [
            $this->requestIdHeaderName => $this->requestId,
            $this->runtimeHeaderName   => $this->getRuntime(),
        ];
    }

    /**
     * Возвращает ошибку если в ответе таковая есть
     * @return Exception|null
     */
    protected function getException(): ?Exception
    {
        $exception = null;
        if (!$this->response->isSuccessful()) {
            $originContent = $this->response->getData();
            if (isset($originContent->exception)) {
                $exception = $this->makeException($originContent->message ?: $originContent->exception, $originContent->code ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $exception;
    }

    /**
     * Генерирует ошибку для форматтера
     *
     * @param string|null $message
     * @param int|null $code
     * @return Exception
     */
    protected function makeException(?string $message = null, ?int $code = null): Exception
    {
        return new Exception($message ?? 'Internal server error', $code ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * возвращает время выполнения кода
     *
     * @return float
     */
    protected function getRuntime(): float
    {
        $res = 0;
        if ($this->finishTime) {
            $res = $this->finishTime - $this->startTime;
        }

        return round($res, 5);
    }
}
