<?php
namespace UserAgentParser\Provider\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use stdClass;
use UserAgentParser\Exception;
use UserAgentParser\Model;

/**
 *
 * @see https://udger.com/support/documentation/?doc=38
 *
 */
class UdgerCom extends AbstractHttpProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'UdgerCom';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'https://udger.com/';

    protected $detectionCapabilities = [

        'browser' => [
            'name'    => true,
            'version' => true,
        ],

        'renderingEngine' => [
            'name'    => true,
            'version' => false,
        ],

        'operatingSystem' => [
            'name'    => true,
            'version' => false,
        ],

        'device' => [
            'model'    => false,
            'brand'    => false,
            'type'     => true,
            'isMobile' => false,
            'isTouch'  => false,
        ],

        'bot' => [
            'isBot' => true,
            'name'  => false,
            'type'  => false,
        ],
    ];

    protected $defaultValues = [
        'general' => [
            '/^unknown$/i',
        ],
    ];

    private static $uri = 'http://api.udger.com/parse';

    private $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        parent::__construct($client);

        $this->apiKey = $apiKey;
    }

    public function getVersion()
    {
        return;
    }

    /**
     *
     * @param  string                     $userAgent
     * @param  array                      $headers
     * @return stdClass
     * @throws Exception\RequestException
     */
    protected function getResult($userAgent, array $headers)
    {
        /*
         * an empty UserAgent makes no sense
         */
        if ($userAgent == '') {
            throw new Exception\NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        $params = [
            'accesskey' => $this->apiKey,
            'uastrig'   => $userAgent,
        ];

        $body = http_build_query($params, null, '&');

        $request = new Request('POST', self::$uri, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], $body);

        $response = $this->getResponse($request);

        /*
         * no json returned?
         */
        $contentType = $response->getHeader('Content-Type');
        if (! isset($contentType[0]) || $contentType[0] != 'application/json') {
            throw new Exception\RequestException('Could not get valid "application/json" response from "' . $request->getUri() . '". Response is "' . $response->getBody()->getContents() . '"');
        }

        $content = json_decode($response->getBody()->getContents());

        /*
         * No result found?
         */
        if (isset($content->flag) && $content->flag == 3) {
            throw new Exception\NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        /*
         * Errors
         */
        if (isset($content->flag) && $content->flag == 4) {
            throw new Exception\InvalidCredentialsException('Your API key "' . $this->apiKey . '" is not valid for ' . $this->getName());
        }

        if (isset($content->flag) && $content->flag == 6) {
            throw new Exception\LimitationExceededException('Exceeded the maximum number of request with API key "' . $this->apiKey . '" for ' . $this->getName());
        }

        if (isset($content->flag) && $content->flag > 3) {
            throw new Exception\RequestException('Could not get valid response from "' . $request->getUri() . '". Response is "' . $response->getBody()->getContents() . '"');
        }

        /*
         * Missing data?
         */
        if (! $content instanceof stdClass || ! isset($content->info)) {
            throw new Exception\RequestException('Could not get valid response from "' . $request->getUri() . '". Response is "' . $response->getBody()->getContents() . '"');
        }

        return $content;
    }

    /**
     *
     * @param  stdClass $resultRaw
     * @return boolean
     */
    private function isBot(stdClass $resultRaw)
    {
        if (isset($resultRaw->type) && $resultRaw->type === 'Robot') {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Model\Bot $bot
     * @param stdClass  $resultRaw
     */
    private function hydrateBot(Model\Bot $bot, stdClass $resultRaw)
    {
        $bot->setIsBot(true);

        if (isset($resultRaw->ua_family) && $this->isRealResult($resultRaw->ua_family) === true) {
            $bot->setName($resultRaw->ua_family);
        }
    }

    /**
     *
     * @param Model\Browser $browser
     * @param stdClass      $resultRaw
     */
    private function hydrateBrowser(Model\Browser $browser, stdClass $resultRaw)
    {
        if (isset($resultRaw->ua_family) && $this->isRealResult($resultRaw->ua_family) === true) {
            $browser->setName($resultRaw->ua_family);
        }

        if (isset($resultRaw->ua_ver) && $this->isRealResult($resultRaw->ua_ver) === true) {
            $browser->getVersion()->setComplete($resultRaw->ua_ver);
        }
    }

    /**
     *
     * @param Model\RenderingEngine $engine
     * @param stdClass              $resultRaw
     */
    private function hydrateRenderingEngine(Model\RenderingEngine $engine, stdClass $resultRaw)
    {
        if (isset($resultRaw->ua_engine) && $this->isRealResult($resultRaw->ua_engine) === true) {
            $engine->setName($resultRaw->ua_engine);
        }
    }

    /**
     *
     * @param Model\OperatingSystem $os
     * @param stdClass              $resultRaw
     */
    private function hydrateOperatingSystem(Model\OperatingSystem $os, stdClass $resultRaw)
    {
        if (isset($resultRaw->os_family) && $this->isRealResult($resultRaw->os_family) === true) {
            $os->setName($resultRaw->os_family);
        }
    }

    /**
     *
     * @param Model\UserAgent $device
     * @param stdClass        $resultRaw
     */
    private function hydrateDevice(Model\Device $device, stdClass $resultRaw)
    {
        if (isset($resultRaw->device_name) && $this->isRealResult($resultRaw->device_name) === true) {
            $device->setType($resultRaw->device_name);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $resultRaw = $this->getResult($userAgent, $headers);

        /*
         * Hydrate the model
         */
        $result = new Model\UserAgent();
        $result->setProviderResultRaw($resultRaw);

        /*
         * Bot detection
         */
        if ($this->isBot($resultRaw->info) === true) {
            $this->hydrateBot($result->getBot(), $resultRaw->info);

            return $result;
        }

        /*
         * hydrate the result
         */
        $this->hydrateBrowser($result->getBrowser(), $resultRaw->info);
        $this->hydrateRenderingEngine($result->getRenderingEngine(), $resultRaw->info);
        $this->hydrateOperatingSystem($result->getOperatingSystem(), $resultRaw->info);
        $this->hydrateDevice($result->getDevice(), $resultRaw->info);

        return $result;
    }
}
