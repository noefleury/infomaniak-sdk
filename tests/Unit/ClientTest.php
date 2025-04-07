<?php

namespace NoeFleury\InfomaniakSdk\Tests\Unit;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Exception\InvalidRequestVerb;
use NoeFleury\InfomaniakSdk\HttpHandler\CurlHttpHandler;
use NoeFleury\InfomaniakSdk\Object\RequestBuilder;
use NoeFleury\InfomaniakSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ClientTest extends TestCase
{

    public function test_api_endpoint()
    {
        $this->assertSame('https://api.infomaniak.com', Client::ENDPOINT);
    }

    public function test_can_instantiate_client()
    {
        $client = new Client();
        $this->assertInstanceOf(Client::class, $client);
        $this->assertNull($client->getBearer());
    }

    public function test_can_instantiate_client_with_bearer()
    {
        $client = new Client('dummy');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('dummy', $client->getBearer());
    }

    public function test_can_instantiate_client_and_set_bearer_later()
    {
        $client = new Client();
        $client->setBearer('dummy');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('dummy', $client->getBearer());
    }

    public function test_can_set_handler()
    {
        $client = new Client('dummy');
        $client->setHandler(new MyCustomCurlHttpHandler());
        $this->assertInstanceOf(MyCustomCurlHttpHandler::class, $client->getHandler());
        $this->assertSame(['uri' => '/1/ping'], $client->getHandler()->debug('/1/ping'));
    }

    #[TestWith(['get'])]
    #[TestWith(['post'])]
    #[TestWith(['patch'])]
    #[TestWith(['put'])]
    #[TestWith(['delete'])]
    public function test_verb_method_builder(string $verb)
    {
        $client = new Client();
        $builder = $client->$verb('/ping');
        $this->assertInstanceOf(RequestBuilder::class, $builder);
    }

    public function test_verb_method_builder_when_invalid_verb()
    {
        $client = new Client('dummy');
        $this->expectExceptionObject(new InvalidRequestVerb('Invalid requested verb: VERB'));
        $client->verb('/1/ressource');
    }

}

/**
 * Just a dummy Handler for our test
 */
readonly class MyCustomCurlHttpHandler extends CurlHttpHandler
{
    public function debug(string $uri): array
    {
        return [
            'uri' => $uri,
        ];
    }
}
