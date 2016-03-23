<?php
namespace UserAgentParser\Provider;

use UAParser\Parser;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Exception\PackageNotLoadedException;
use UserAgentParser\Model;

class UAParser extends AbstractProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'UAParser';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'https://github.com/ua-parser/uap-php';

    /**
     * Composer package name
     *
     * @var string
     */
    protected $packageName = 'ua-parser/uap-php';

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
            'model'    => true,
            'brand'    => true,
            'type'     => false,
            'isMobile' => false,
            'isTouch'  => false,
        ],

        'bot' => [
            'isBot' => true,
            'name'  => true,
            'type'  => false,
        ],
    ];

    protected $defaultValues = [

        'general' => [
            '/^Other$/i',
        ],

        'device' => [

            'brand' => [
                '/^Generic/i',
            ],

            'model' => [
                '/^Smartphone$/i',
                '/^Feature Phone$/i',
                '/^iOS-Device$/i',
                '/^Tablet$/i',
                '/^Touch$/i',
                '/^Windows$/i',
                '/^Windows Phone$/i',
            ],
        ],

        'bot' => [
            'name' => [
                '/^Other$/i',
                '/^crawler$/i',
                '/^robot$/i',
                '/^crawl$/i',
                '/^Spider$/i',
            ],
        ],
    ];

    private $parser;

    /**
     *
     * @param  Parser                    $parser
     * @throws PackageNotLoadedException
     */
    public function __construct(Parser $parser = null)
    {
        if ($parser === null && ! file_exists('vendor/' . $this->getPackageName() . '/composer.json')) {
            throw new PackageNotLoadedException('You need to install the package ' . $this->getPackageName() . ' to use this provider');
        }

        $this->parser = $parser;
    }

    /**
     *
     * @return Parser
     */
    public function getParser()
    {
        if ($this->parser !== null) {
            return $this->parser;
        }

        $this->parser = Parser::create();

        return $this->parser;
    }

    /**
     *
     * @param \UAParser\Result\Client $resultRaw
     *
     * @return bool
     */
    private function hasResult(\UAParser\Result\Client $resultRaw)
    {
        if ($this->isBot($resultRaw) === true) {
            return true;
        }

        if ($this->isRealResult($resultRaw->ua->family)) {
            return true;
        }

        if ($this->isRealResult($resultRaw->os->family)) {
            return true;
        }

        if ($this->isRealResult($resultRaw->device->model, 'device', 'model')) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param \UAParser\Result\Client $resultRaw
     *
     * @return bool
     */
    private function isBot(\UAParser\Result\Client $resultRaw)
    {
        if ($resultRaw->device->family === 'Spider') {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Model\Bot               $bot
     * @param \UAParser\Result\Client $resultRaw
     */
    private function hydrateBot(Model\Bot $bot, \UAParser\Result\Client $resultRaw)
    {
        $bot->setIsBot(true);

        if ($this->isRealResult($resultRaw->ua->family, 'bot', 'name') === true) {
            $bot->setName($resultRaw->ua->family);
        }
    }

    /**
     *
     * @param Model\Browser              $browser
     * @param \UAParser\Result\UserAgent $uaRaw
     */
    private function hydrateBrowser(Model\Browser $browser, \UAParser\Result\UserAgent $uaRaw)
    {
        if ($this->isRealResult($uaRaw->family) === true) {
            $browser->setName($uaRaw->family);
        }

        if ($this->isRealResult($uaRaw->major) === true) {
            $browser->getVersion()->setMajor($uaRaw->major);
        }

        if ($this->isRealResult($uaRaw->minor) === true) {
            $browser->getVersion()->setMinor($uaRaw->minor);
        }

        if ($this->isRealResult($uaRaw->patch) === true) {
            $browser->getVersion()->setPatch($uaRaw->patch);
        }
    }

    /**
     *
     * @param Model\OperatingSystem            $os
     * @param \UAParser\Result\OperatingSystem $osRaw
     */
    private function hydrateOperatingSystem(Model\OperatingSystem $os, \UAParser\Result\OperatingSystem $osRaw)
    {
        if ($this->isRealResult($osRaw->family) === true) {
            $os->setName($osRaw->family);
        }

        if ($this->isRealResult($osRaw->major) === true) {
            $os->getVersion()->setMajor($osRaw->major);
        }

        if ($this->isRealResult($osRaw->minor) === true) {
            $os->getVersion()->setMinor($osRaw->minor);
        }

        if ($this->isRealResult($osRaw->patch) === true) {
            $os->getVersion()->setPatch($osRaw->patch);
        }
    }

    /**
     *
     * @param Model\UserAgent         $device
     * @param \UAParser\Result\Device $deviceRaw
     */
    private function hydrateDevice(Model\Device $device, \UAParser\Result\Device $deviceRaw)
    {
        if ($this->isRealResult($deviceRaw->model, 'device', 'model') === true) {
            $device->setModel($deviceRaw->model);
        }

        if ($this->isRealResult($deviceRaw->brand, 'device', 'brand') === true) {
            $device->setBrand($deviceRaw->brand);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $parser = $this->getParser();

        /* @var $resultRaw \UAParser\Result\Client */
        $resultRaw = $parser->parse($userAgent);

        /*
         * No result found?
         */
        if ($this->hasResult($resultRaw) !== true) {
            throw new NoResultFoundException('No result found for user agent: ' . $userAgent);
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
        $this->hydrateBrowser($result->getBrowser(), $resultRaw->ua);
        // renderingEngine not available
        $this->hydrateOperatingSystem($result->getOperatingSystem(), $resultRaw->os);
        $this->hydrateDevice($result->getDevice(), $resultRaw->device);

        return $result;
    }
}
