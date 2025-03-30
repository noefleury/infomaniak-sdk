<?php

namespace NoeFleury\InfomaniakSdk\Object;

use NoeFleury\InfomaniakSdk\Exception\Http\HttpException;
use NoeFleury\InfomaniakSdk\Exception\Http\MethodNotFound;
use NoeFleury\InfomaniakSdk\Exception\Http\ObjectNotFound;
use NoeFleury\InfomaniakSdk\Exception\Http\ValidationFailed;
use NoeFleury\InfomaniakSdk\Exception\InvalidResponse;
use NoeFleury\InfomaniakSdk\Exception\UnexpectedResponse;

class Response
{

    protected const string RESULT_ON_SUCCESS = 'success';
    protected const string RESULT_ON_ERROR = 'error';

    protected const string ERROR_METHOD_NOT_FOUND = 'method_not_found';
    protected const string ERROR_OBJECT_NOT_FOUND = 'object_not_found';
    protected const string ERROR_VALIDATION_FAILED = 'validation_failed';

    protected array|string $data;

    public function __construct(string $rawData, int $httpCode)
    {
        return $this->handleResponse($rawData, $httpCode);
    }

    public function data()
    {
        return $this->data;
    }

    protected function handleResponse(string $rawData, int $httpCode)
    {
        throw_if(empty($rawData), UnexpectedResponse::class);

        try {
            $response = json_decode($rawData, true);
            switch ($response['result']) {
                case self::RESULT_ON_SUCCESS:
                    $this->data = $response['data'];
                    break;
                case self::RESULT_ON_ERROR:
                    $this->handleErrorResponse($response['error'], $httpCode);
                    break;
            }
        } catch (HttpException $httpException) {
            throw $httpException;
        } catch (\Throwable $throwable) {
            throw new InvalidResponse();
        }
    }

    /**
     * Throw HTTP exception according to given error response
     *
     * @param  array  $error
     * @param  int  $httpCode
     *
     * @return mixed
     *
     * @throws HttpException
     */
    protected function handleErrorResponse(array $error, int $httpCode)
    {
        /** @var HttpException $httpExceptionClass */
        $httpExceptionClass = match ($error['code']) {
            self::ERROR_METHOD_NOT_FOUND => MethodNotFound::class,
            self::ERROR_OBJECT_NOT_FOUND => ObjectNotFound::class,
            self::ERROR_VALIDATION_FAILED => ValidationFailed::class,
            default => HttpException::class,
        };
        throw new $httpExceptionClass($error['description'], $httpCode);
    }

}
