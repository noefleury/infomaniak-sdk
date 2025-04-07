<?php

namespace NoeFleury\InfomaniakSdk;

use NoeFleury\InfomaniakSdk\Enum\HttpVerb;
use NoeFleury\InfomaniakSdk\Exception\InvalidRequestVerb;
use NoeFleury\InfomaniakSdk\Exception\MissingBearerToken;
use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;
use NoeFleury\InfomaniakSdk\HttpHandler\HttpHandlerInterface;
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
    protected ?HttpHandlerInterface $handler = null;

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

    /**
     * @throws MissingBearerToken
     */
    public function setHandler(HttpHandlerInterface $handler): void
    {
        if (empty($this->getBearer())) {
            throw new MissingBearerToken();
        }
        $this->handler = $handler;
        $this->handler->initiate(self::ENDPOINT, $this->getBearer());
    }

    /**
     * @throws MissingBearerToken
     */
    public function useDefaultHandler(): void
    {
        $this->setHandler(new CurlHttpHandler());
    }

    /**
     * Get HTTP handler
     * If none was explicitly set -> fallback on default one
     *
     * @return HttpHandlerInterface
     * @throws MissingBearerToken
     */
    public function getHandler(): HttpHandlerInterface
    {
        if (empty($this->handler)) {
            $this->useDefaultHandler();
        }
        return $this->handler;
    }

    /**
     * @throws InvalidRequestVerb
     */
    public function __call(string $verb, array $uriAndParams)
    {
        $verb = strtoupper($verb);
        $httpVerb = HttpVerb::tryFrom($verb);
        if (is_null($httpVerb)) {
            throw new InvalidRequestVerb("Invalid requested verb: $verb");
        }
        return new RequestBuilder($this, $httpVerb, ...$uriAndParams);
    }

}
