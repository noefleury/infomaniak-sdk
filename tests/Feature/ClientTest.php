<?php

namespace NoeFleury\InfomaniakSdk\Tests\Feature;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Exception\InvalidRequestVerb;
use NoeFleury\InfomaniakSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Client::class)]
class ClientTest extends TestCase
{
    public function test_requesting_with_invalid_verb()
    {
        $client = new Client('dummy');
        $this->expectExceptionObject(new InvalidRequestVerb('Invalid requested verb: VERB'));
        $client->verb('/1/ressource');
    }

}
