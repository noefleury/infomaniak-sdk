<?php

namespace NoeFleury\InfomaniakSdk\HttpHandler;

use NoeFleury\InfomaniakSdk\Object\Response;

interface HttpHandlerInterface
{

    public function initiate(string $apiEndpoint, string $bearerToken): void;

    public function get(string $uri, array $queryParams): Response;

    public function post(string $uri, array $payload): Response;

    public function patch(string $uri, array $payload): Response;

    public function put(string $uri, array $payload): Response;
    
    public function delete(string $uri): Response;

}