<?php
namespace UserAgentParser\Provider\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException as GuzzleHttpException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use UserAgentParser\Exception;
use UserAgentParser\Provider\AbstractProvider;

abstract class AbstractHttpProvider extends AbstractProvider
{
    /**
     *
     * @var Client
     */
    private $client;

    /**
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *
     * @param  RequestInterface           $request
     * @return Response
     * @throws Exception\RequestException
     */
    protected function getResponse(RequestInterface $request)
    {
        try {
            /* @var $response \GuzzleHttp\Psr7\Response */
            $response = $this->getClient()->send($request);
        } catch (GuzzleHttpException $ex) {
            throw new Exception\RequestException('Could not get valid response from "' . $request->getUri() . '"', null, $ex);
        }

        if ($response->getStatusCode() !== 200) {
            throw new Exception\RequestException('Could not get valid response from "' . $request->getUri() . '". Status code is: "' . $response->getStatusCode() . '"');
        }

        return $response;
    }
}
