<?php

namespace NoeFleury\InfomaniakSdk\Object;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Enum\RequestBuilder\OrderDirection;
use NoeFleury\InfomaniakSdk\Exception\InvalidRequestVerb;
use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;
use NoeFleury\InfomaniakSdk\HttpHandler\HttpHandlerInterface;

class RequestBuilder
{

    protected array $with = [];
    protected string $orderBy;
    protected OrderDirection $orderDirection;

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

    public function orderBy(string $attribute, ?OrderDirection $direction = OrderDirection::Ascendant): self
    {
        $this->orderBy = $attribute;
        if (!is_null($direction)) {
            $this->orderDirection($direction);
        }
        return $this;
    }

    public function orderDirection(OrderDirection $direction): self
    {
        $this->orderDirection = $direction;
        return $this;
    }

    public function ascendant(): self
    {
        $this->orderDirection(OrderDirection::Ascendant);
        return $this;
    }

    public function descendant(): self
    {
        $this->orderDirection(OrderDirection::Descendant);
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
        if (!empty($this->orderBy)) {
            $this->payload['order_by'] = $this->orderBy;
            if (!empty($this->orderDirection)) {
                $this->payload['order'] = $this->orderDirection->value;
            }
        }
    }

}
