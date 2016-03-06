<?php
namespace UserAgentParser\Provider\Http;

use GuzzleHttp\Psr7\Request;
use stdClass;
use UserAgentParser\Exception;
use UserAgentParser\Model;

/**
 *
 * @see http://www.useragentstring.com/pages/api.php
 */
class UserAgentStringCom extends AbstractHttpProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'UserAgentStringCom';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'http://www.useragentstring.com/';

    protected $detectionCapabilities = [

        'browser' => [
            'name'    => true,
            'version' => true,
        ],

        'renderingEngine' => [
            'name'    => false,
            'version' => false,
        ],

        'operatingSystem' => [
            'name'    => true,
            'version' => true,
        ],

        'device' => [
            'model'    => false,
            'brand'    => false,
            'type'     => false,
            'isMobile' => false,
            'isTouch'  => false,
        ],

        'bot' => [
            'isBot' => true,
            'name'  => true,
            'type'  => true,
        ],
    ];

    protected $defaultValues = [
        'general' => [
            '/^unknown$/i',
        ],
    ];

    private $botTypes = [
        'Crawler',
        'Cloud Platform',
        'Feed Reader',
        'LinkChecker',
        'Validator',
    ];

    private static $uri = 'http://www.useragentstring.com/';

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

        $parameters = 'uas=' . urlencode($userAgent);
        $parameters .= '&getJSON=all';

        $uri = self::$uri . '?' . $parameters;

        $request = new Request('GET', $uri);

        $response = $this->getResponse($request);

        /*
         * no json returned?
         */
        $contentType = $response->getHeader('Content-Type');
        if (! isset($contentType[0]) || $contentType[0] != 'application/json') {
            throw new Exception\RequestException('Could not get valid "application/json" response from "' . $request->getUri() . '". Response is "' . $response->getBody()->getContents() . '"');
        }

        $content = json_decode($response->getBody()->getContents());

        if (! $content instanceof stdClass) {
            throw new Exception\RequestException('Could not get valid response from "' . $request->getUri() . '". Response is "' . $response->getBody()->getContents() . '"');
        }

        return $content;
    }

    /**
     *
     * @param stdClass $resultRaw
     *
     * @return bool
     */
    private function hasResult(stdClass $resultRaw)
    {
        if (isset($resultRaw->agent_type) && $this->isRealResult($resultRaw->agent_type)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param  stdClass $resultRaw
     * @return boolean
     */
    private function isBot(stdClass $resultRaw)
    {
        if (isset($resultRaw->agent_type) && in_array($resultRaw->agent_type, $this->botTypes)) {
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

        if (isset($resultRaw->agent_name) && $this->isRealResult($resultRaw->agent_name) === true) {
            $bot->setName($resultRaw->agent_name);
        }

        if (isset($resultRaw->agent_type) && $this->isRealResult($resultRaw->agent_type) === true) {
            $bot->setType($resultRaw->agent_type);
        }
    }

    /**
     *
     * @param Model\Browser $browser
     * @param stdClass      $resultRaw
     */
    private function hydrateBrowser(Model\Browser $browser, stdClass $resultRaw)
    {
        if (isset($resultRaw->agent_name) && $this->isRealResult($resultRaw->agent_name) === true) {
            $browser->setName($resultRaw->agent_name);
        }

        if (isset($resultRaw->agent_version) && $this->isRealResult($resultRaw->agent_version) === true) {
            $version = preg_replace('/(\d*)_(\d*)/', '$1.$2', $resultRaw->agent_version);

            $browser->getVersion()->setComplete($version);
        }
    }

    /**
     *
     * @param Model\OperatingSystem $os
     * @param stdClass              $resultRaw
     */
    private function hydrateOperatingSystem(Model\OperatingSystem $os, stdClass $resultRaw)
    {
        if (isset($resultRaw->os_name) && $this->isRealResult($resultRaw->os_name) === true) {
            $os->setName($resultRaw->os_name);
        }

        if (isset($resultRaw->os_versionNumber) && $this->isRealResult($resultRaw->os_versionNumber) === true) {
            $version = preg_replace('/(\d*)_(\d*)/', '$1.$2', $resultRaw->os_versionNumber);

            $os->getVersion()->setComplete($version);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $resultRaw = $this->getResult($userAgent, $headers);

        /*
         * No result found?
         */
        if ($this->hasResult($resultRaw) !== true) {
            throw new Exception\NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        /*
         * Hydrate the model
         */
        $result = new Model\UserAgent();
        $result->setProviderResultRaw($resultRaw);

        /*
         * Bot detection
         */
        if ($this->isBot($resultRaw) === true) {
            $this->hydrateBot($result->getBot(), $resultRaw);

            return $result;
        }

        /*
         * hydrate the result
         */
        $this->hydrateBrowser($result->getBrowser(), $resultRaw);
        $this->hydrateOperatingSystem($result->getOperatingSystem(), $resultRaw);

        return $result;
    }
}
