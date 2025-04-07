<?php

namespace NoeFleury\InfomaniakSdk\HttpHandler;

abstract readonly class BaseHandler implements HttpHandlerInterface
{

    protected string $apiEndpoint;
    protected string $bearerToken;

    public function initiate(string $apiEndpoint, string $bearerToken): void
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->bearerToken = $bearerToken;
    }

}