<?php

namespace NoeFleury\InfomaniakSdk\Tests\Feature;

use NoeFleury\InfomaniakSdk\Client;
use NoeFleury\InfomaniakSdk\Exception\MissingBearerToken;
use NoeFleury\InfomaniakSdk\HttpHandler\HttpHandlerInterface;
use NoeFleury\InfomaniakSdk\Object\Response;
use NoeFleury\InfomaniakSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ClientTest extends TestCase
{

    private function getSuccessMockCurlHttpHandler(
        string $expectedVerb,
        string $expectedUri,
        array $expectedPayload,
        array|string|int $response,
    ) {
        $curlHandlerMock = $this->createMock(HttpHandlerInterface::class);
        $curlHandlerMock
            ->expects($this->once())
            ->method($expectedVerb)
            ->with(
                $expectedUri,
                $expectedPayload,
            )->willReturn(
                new Response(json_encode(['result' => 'success', 'data' => $response]), 200)
            );
        return $curlHandlerMock;
    }

    public function test_get_ressource_without_token()
    {
        $client = new Client();
        $this->expectExceptionObject(new MissingBearerToken());
        $client->get('/1/ressource')->send();
    }

    #[TestWith(['get'])]
    #[TestWith(['post'])]
    #[TestWith(['patch'])]
    #[TestWith(['put'])]
    #[TestWith(['delete'])]
    public function test_successful_requests(string $verb)
    {
        $curlHandlerMock = $this->getSuccessMockCurlHttpHandler($verb, '/1/ressource', ['payload' => 'dummy'], 'dummy');
        $client = new Client('dummy');
        $client->setHandler($curlHandlerMock);
        $response = $client->$verb('/1/ressource', ['payload' => 'dummy'])->send();
        $this->assertSame('dummy', $response->data());
    }

}
