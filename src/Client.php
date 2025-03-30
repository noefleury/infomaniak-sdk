<?php

namespace NoeFleury\InfomaniakSdk;

use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;

class Client extends CurlHttpHandler
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

}
