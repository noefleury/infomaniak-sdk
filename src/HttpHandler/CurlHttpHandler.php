<?php

namespace NoeFleury\InfomaniakSdk\HttpHandler;

use NoeFleury\InfomaniakSdk\Object\Response;

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

    public function get(string $uri, ?array $queryParams = null): Response
    {
        if (!empty($queryParams)) {
            $uri = $uri.'?'.http_build_query($queryParams);
        }
        $curl = $this->buildCurl($uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return new Response($response, $httpCode);
    }

    private function requestWithPostFields(string $method, string $uri, ?array $payload): Response
    {
        $curl = $this->buildCurl($uri);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return new Response($response, $httpCode);
    }

    public function post(string $uri, array $payload): Response
    {
        return $this->requestWithPostFields('POST', $uri, $payload);
    }

    public function patch(string $uri, array $payload): Response
    {
        return $this->requestWithPostFields('PATCH', $uri, $payload);
    }

    public function put(string $uri, array $payload): Response
    {
        return $this->requestWithPostFields('PUT', $uri, $payload);
    }

    public function delete(string $uri): Response
    {
        return $this->requestWithPostFields('DELETE', $uri, null);
    }

}
