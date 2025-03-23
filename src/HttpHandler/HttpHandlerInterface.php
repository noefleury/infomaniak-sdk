<?php

namespace NoeFleury\InfomaniakSdk\HttpHandler;

interface HttpHandlerInterface
{

    public function get(string $uri, array $queryParams);

    public function post(string $uri, array $payload);

    public function patch(string $uri, array $payload);

    public function put(string $uri, array $payload);

    public function delete(string $uri);

}