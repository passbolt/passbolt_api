<?php
namespace UserAgentParserTest\Unit\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\UserAgent;

abstract class AbstractProviderTestCase extends PHPUnit_Framework_TestCase
{
    public function assertProviderResult($result, array $expectedResult)
    {
        $this->assertInstanceOf('UserAgentParser\Model\UserAgent', $result);

        $model          = new UserAgent();
        $expectedResult = array_merge($model->toArray(), $expectedResult);

        $this->assertEquals($result->toArray(), $expectedResult);
    }

    /**
     *
     * @return Client
     */
    protected function getClient(array $responseQueue = [])
    {
        $mock = new MockHandler($responseQueue);

        $handler = HandlerStack::create($mock);

        $client = new Client([
            'handler' => $handler,
        ]);

        return $client;
    }
}
