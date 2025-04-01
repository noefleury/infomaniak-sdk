<?php

namespace NoeFleury\InfomaniakSdk\Object;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Exception\InvalidRequestVerb;
use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;
use NoeFleury\InfomaniakSdk\HttpHandler\HttpHandlerInterface;

class RequestBuilder
{

    protected array $with = [];

    protected HttpHandlerInterface $handler;

    public function __construct(
        protected Client $client,
        protected string $method,
        protected string $uri,
        protected ?array $payload = [],
    ) {
        throw_unless(in_array($this->method, ['GET', 'POST', 'PATCH', 'PUT', 'DELETE']), new InvalidRequestVerb());
    }

    public function with(string $attribute): self
    {
        if (!in_array($attribute, $this->with)) {
            $this->with[] = $attribute;
        }
        return $this;
    }

    /**
     * Send request
     *
     * @return Response
     */
    public function send(): Response
    {
        if (empty($this->handler)) {
            $this->handler = new CurlHttpHandler($this->client::ENDPOINT, $this->client->getBearer());
        }
        $this->consolidatePayload();
        return $this->handler->{$this->method}($this->uri, $this->payload);
    }

    /**
     * Consolidate payload from all what we've accumulated through builder methods
     * @return void
     */
    protected function consolidatePayload(): void
    {
        if (!empty($this->with)) {
            $this->payload['with'] = implode(',', $this->with);
        }
    }

}
