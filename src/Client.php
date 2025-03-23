<?php

namespace NoeFleury\InfomaniakSdk;

use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;

class Client extends CurlHttpHandler
{

    protected ?string $bearer = null;
    public const ENDPOINT = 'https://api.infomaniak.com';

    public function __construct(?string $bearerToken = null)
    {
        $this->bearer = $bearerToken;
    }

    public function setBearer(string $token): self
    {
        $this->bearer = $token;
        return $this;
    }

}