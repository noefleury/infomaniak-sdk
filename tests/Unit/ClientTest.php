<?php

namespace NoeFleury\InfomaniakSdk\Tests\Unit;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Client::class)]
class ClientTest extends TestCase
{
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

    public function test_api_endpoint()
    {
        $this->assertSame('https://api.infomaniak.com', Client::ENDPOINT);
    }

}
