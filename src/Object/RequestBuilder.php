<?php

namespace NoeFleury\InfomaniakSdk\Object;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Enum\HttpVerb;
use NoeFleury\InfomaniakSdk\Exception\MissingBearerToken;
use NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin\OffsetPlugin;
use NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin\SortingPlugin;
use NoeFleury\InfomaniakSdk\Helper\RequestBuilderPlugin\WithPlugin;

class RequestBuilder
{

    use WithPlugin;
    use SortingPlugin;
    use OffsetPlugin;

    public function __construct(
        protected Client $client,
        protected HttpVerb $verb,
        protected string $uri,
        protected ?array $payload = [],
    ) {
    }

    /**
     * Send request
     *
     * @return Response
     * @throws MissingBearerToken
     */
    public function send(): Response
    {
        $this->consolidatePayload();
        return $this->client->getHandler()->{$this->verb->value}($this->uri, $this->payload);
    }

    /**
     * Consolidate payload from all what we've accumulated through builder methods
     * This will call each plugin payload building method.
     * @return void
     */
    protected function consolidatePayload(): void
    {
        $this->handleWithPayload(); // WithPlugin
        $this->handleSortingPayload(); // SortingPlugin
        $this->handleOffsetPayload(); // OffsetPlugin
    }

}
