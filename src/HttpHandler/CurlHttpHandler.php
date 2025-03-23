<?php

namespace NoeFleury\InfomaniakSdk\HttpHandler;

abstract class CurlHttpHandler implements HttpHandlerInterface
{

    private function buildCurl(string $uri): \CurlHandle|false
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::ENDPOINT.'/'.ltrim($uri, '/'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$this->bearer,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }

    public function get(string $uri, ?array $queryParams = null): string|false
    {
        if (!empty($queryParams)) {
            $uri = $uri.'?'.http_build_query($queryParams);
        }
        $curl = $this->buildCurl($uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function requestWithPostFields(string $method, string $uri, ?array $payload): string|false
    {
        $curl = $this->buildCurl($uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function post(string $uri, array $payload): string|false
    {
        return $this->requestWithPostFields('POST', $uri, $payload);
    }

    public function patch(string $uri, array $payload): string|false
    {
        return $this->requestWithPostFields('PATCH', $uri, $payload);
    }

    public function put(string $uri, array $payload): string|false
    {
        return $this->requestWithPostFields('PUT', $uri, $payload);
    }

    public function delete(string $uri): string|false
    {
        return $this->requestWithPostFields('DELETE', $uri, null);
    }

}