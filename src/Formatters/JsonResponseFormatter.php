<?php
declare(strict_types = 1);

namespace Patrikap\Apiator\Formatters;

use Throwable;
use Illuminate\Http\JsonResponse;
use Patrikap\Apiator\Contracts\JsonResponseFormatterInterface;

/**
 * Class JsonResponseFormatter
 * @package Patrikap\Apiator\Formatters
 *
 * Base formatter to API response
 *
 * @author Konstantin.K
 */
class JsonResponseFormatter implements JsonResponseFormatterInterface
{
    /** @var array formatting json */
    private array $json = [];
    /** @var array|string[] error message collection */
    private array $messageCollection = [
        400 => 'Bad request',
        401 => 'Access denied',
        403 => 'Forbidden',
        404 => 'Not found',
        405 => 'Method not allowed',
        415 => 'Unsupported Media Type',
        419 => 'Authentication Timeout',
        422 => 'Unprocessable Entity',
        500 => 'Internal server error',
        501 => 'Not Implemented',
    ];

    /**
     * JsonResponseFormatter constructor.
     */
    public function __construct()
    {
        $this->resetJson();
    }

    /**
     * Переустанавливает форматированный массив возвращаемых данных
     *
     * @return $this
     */
    private function resetJson(): self
    {
        $this->json = [
            "success" => true,
            "code"    => 0,
            "message" => null,
            "data"    => null,
            "locale"  => config('app.locale'),
        ];
        if ($v = config('app.version', null)) {
            $this->json['version'] = $v;
        }

        return $this;
    }

    /**
     * fluent setter for flag of success
     *
     * @param bool $success
     * @return $this
     */
    private function setSuccess(bool $success): self
    {
        $this->json['success'] = $success;

        return $this;
    }

    /**
     * fluent setter for code response
     *
     * @param int $code
     * @return $this
     */
    private function setCode(int $code): self
    {
        $this->json['code'] = $code;

        return $this;
    }

    /**
     * fluent setter for message response
     *
     * @param string $message
     * @return $this
     */
    private function setMessage(string $message): self
    {
        $this->json['message'] = $message;

        return $this;
    }

    /**
     * fluent setter for data response
     *
     * @param $data
     * @return $this
     */
    private function setData($data): self
    {
        $this->json['data'] = $data;

        return $this;
    }

    /**
     * hiding internal exceptions of the framework
     *
     * @param int $code
     */
    private function getErrorMessage(int $code): string
    {
        if (isset($this->messageCollection[$code])) {
            return $this->messageCollection[$code];
        }

        return 'Undefined error, please contact with developers';
    }
    /***************************************/
    /** @inheritDoc */
    public function formatResponseWithException(Throwable $exception, int $statusCode): self
    {
        $this->resetJson();
        $this->setSuccess(false)
            ->setCode($statusCode)
            ->setMessage($this->getErrorMessage($statusCode));
            //->setMessage($exception->getMessage());

        return $this;
    }

    /** @inheritDoc */
    public function formatResponse(JsonResponse $response): self
    {
        $this->resetJson();
        $this->setSuccess($response->isSuccessful())
            ->setCode($response->getStatusCode())
            ->setMessage($response->isSuccessful() ? 'OK' : 'ERROR')
            ->setData($response->getData());

        return $this;
    }

    /** @inheritDoc */
    public function getJson(): array
    {
        return $this->json;
    }

    /** @inheritDoc */
    public function setAdditionalInfo($key, $val): self
    {
        if (!isset($this->json['info'])) {
            $this->json['info'] = [];
        }
        $this->json['info'][$key] = $val;

        return $this;
    }
}
