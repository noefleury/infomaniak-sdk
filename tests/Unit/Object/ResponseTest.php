<?php

namespace NoeFleury\InfomaniakSdk\Tests\Unit\Object;

use NoeFleury\InfomaniakSdk\Exception\Http\HttpException;
use NoeFleury\InfomaniakSdk\Exception\Http\MethodNotFound;
use NoeFleury\InfomaniakSdk\Exception\Http\ObjectNotFound;
use NoeFleury\InfomaniakSdk\Exception\Http\ValidationFailed;
use NoeFleury\InfomaniakSdk\Exception\InvalidResponse;
use NoeFleury\InfomaniakSdk\Exception\UnexpectedResponse;
use NoeFleury\InfomaniakSdk\Object\Response;
use NoeFleury\InfomaniakSdk\Tests\TestCase;

class ResponseTest extends TestCase
{

    /** success */

    public function test_response_handle_success_when_data_array()
    {
        $rawData = json_encode(['result' => 'success', 'data' => ['id' => 123]]);
        $response = new Response($rawData, 200);
        $this->assertSame(['id' => 123], $response->data());
    }

    public function test_response_handle_success_when_data_string()
    {
        $rawData = json_encode(['result' => 'success', 'data' => '123']);
        $response = new Response($rawData, 200);
        $this->assertSame('123', $response->data());
    }

    public function test_response_handle_success_when_data_int()
    {
        $rawData = json_encode(['result' => 'success', 'data' => 123]);
        $response = new Response($rawData, 200);
        $this->assertSame(123, $response->data());
    }

    /** error */

    public function test_response_handle_error_method_not_found()
    {
        $rawData = json_encode([
            'result' => 'error',
            'error' => ['code' => 'method_not_found', 'description' => 'Method not found'],
        ]);
        $this->expectExceptionObject(new MethodNotFound('Method not found', 404));
        new Response($rawData, 404);
    }

    public function test_response_handle_error_object_not_found()
    {
        $rawData = json_encode([
            'result' => 'error',
            'error' => ['code' => 'object_not_found', 'description' => 'Object not found'],
        ]);
        $this->expectExceptionObject(new ObjectNotFound('Object not found', 404));
        new Response($rawData, 404);
    }

    public function test_response_handle_error_validation_failed()
    {
        $rawData = json_encode([
            'result' => 'error',
            'error' => ['code' => 'validation_failed', 'description' => 'Validation failed'],
        ]);
        $this->expectExceptionObject(new ValidationFailed('Validation failed', 422));
        new Response($rawData, 422);
    }

    public function test_response_handle_error_not_handled_by_sdk()
    {
        $rawData = json_encode([
            'result' => 'error',
            'error' => ['code' => 'im_a_teapot', 'description' => 'I\'m a teapot'],
        ]);
        $this->expectExceptionObject(new HttpException('I\'m a teapot', 418));
        new Response($rawData, 418);
    }

    public function test_response_handle_empty_response()
    {
        $rawData = '';
        $this->expectExceptionObject(new UnexpectedResponse());
        new Response($rawData, 0);
    }

    public function test_response_handle_invalid_response()
    {
        $rawData = json_encode(['raw']);
        $this->expectExceptionObject(new InvalidResponse());
        new Response($rawData, 200);
    }

    public function test_response_handle_abnormal_response()
    {
        $rawData = json_encode(['result' => 'strange']);
        $this->expectExceptionObject(new InvalidResponse());
        new Response($rawData, 200);
    }


}
