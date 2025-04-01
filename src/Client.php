<?php

namespace NoeFleury\InfomaniakSdk;

use NoeFleury\InfomaniakSdk\Object\RequestBuilder;

/**
 * @method RequestBuilder get(string $uri, ?array $queryParams = [])
 * @method RequestBuilder post(string $uri, ?array $payload = [])
 * @method RequestBuilder patch(string $uri, ?array $payload = [])
 * @method RequestBuilder put(string $uri, ?array $payload = [])
 * @method RequestBuilder delete(string $uri)
 */
class Client
{

    public const string ENDPOINT = 'https://api.infomaniak.com';

    protected ?string $bearer = null;

    public function __construct(?string $bearerToken = null)
    {
        $this->bearer = $bearerToken;
    }

    public function setBearer(string $token): self
    {
        $this->bearer = $token;
        return $this;
    }

    public function getBearer(): ?string
    {
        return $this->bearer;
    }

    public function __call(string $verb, array $uriAndParams)
    {
        $verb = strtoupper($verb);
        if (in_array($verb, ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'])) {
            return new RequestBuilder($this, $verb, ...$uriAndParams);
        }
    }

}
