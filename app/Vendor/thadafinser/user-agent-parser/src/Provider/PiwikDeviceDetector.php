<?php
namespace UserAgentParser\Provider;

use DeviceDetector\DeviceDetector;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Exception\PackageNotLoadedException;
use UserAgentParser\Model;

class PiwikDeviceDetector extends AbstractProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'PiwikDeviceDetector';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'https://github.com/piwik/device-detector';

    /**
     * Composer package name
     *
     * @var string
     */
    protected $packageName = 'piwik/device-detector';

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
            'version' => true,
        ],

        'device' => [
            'model'    => true,
            'brand'    => true,
            'type'     => true,
            'isMobile' => true,
            'isTouch'  => true,
        ],

        'bot' => [
            'isBot' => true,
            'name'  => true,
            'type'  => true,
        ],
    ];

    protected $defaultValues = [

        'general' => [
            '/^UNK$/i',
        ],

        'bot' => [
            'name' => [
                '/^Bot$/i',
                '/^Generic Bot$/i',
            ],
        ],
    ];

    /**
     *
     * @var DeviceDetector
     */
    private $parser;

    /**
     *
     * @param  DeviceDetector            $parser
     * @throws PackageNotLoadedException
     */
    public function __construct(DeviceDetector $parser = null)
    {
        if ($parser === null && ! file_exists('vendor/' . $this->getPackageName() . '/composer.json')) {
            throw new PackageNotLoadedException('You need to install the package ' . $this->getPackageName() . ' to use this provider');
        }

        $this->parser = $parser;
    }

    /**
     *
     * @return DeviceDetector
     */
    public function getParser()
    {
        if ($this->parser !== null) {
            return $this->parser;
        }

        $this->parser = new DeviceDetector();

        return $this->parser;
    }

    /**
     *
     * @param DeviceDetector $dd
     *
     * @return array
     */
    private function getResultRaw(DeviceDetector $dd)
    {
        $raw = [
            'client'          => $dd->getClient(),
            'operatingSystem' => $dd->getOs(),

            'device' => [
                'brand'     => $dd->getBrand(),
                'brandName' => $dd->getBrandName(),

                'model' => $dd->getModel(),

                'device'     => $dd->getDevice(),
                'deviceName' => $dd->getDeviceName(),
            ],

            'bot' => $dd->getBot(),

            'extra' => [
                'isBot' => $dd->isBot(),

                // client
                'isBrowser'     => $dd->isBrowser(),
                'isFeedReader'  => $dd->isFeedReader(),
                'isMobileApp'   => $dd->isMobileApp(),
                'isPIM'         => $dd->isPIM(),
                'isLibrary'     => $dd->isLibrary(),
                'isMediaPlayer' => $dd->isMediaPlayer(),

                // deviceType
                'isCamera'              => $dd->isCamera(),
                'isCarBrowser'          => $dd->isCarBrowser(),
                'isConsole'             => $dd->isConsole(),
                'isFeaturePhone'        => $dd->isFeaturePhone(),
                'isPhablet'             => $dd->isPhablet(),
                'isPortableMediaPlayer' => $dd->isPortableMediaPlayer(),
                'isSmartDisplay'        => $dd->isSmartDisplay(),
                'isSmartphone'          => $dd->isSmartphone(),
                'isTablet'              => $dd->isTablet(),
                'isTV'                  => $dd->isTV(),

                // other special
                'isDesktop'      => $dd->isDesktop(),
                'isMobile'       => $dd->isMobile(),
                'isTouchEnabled' => $dd->isTouchEnabled(),
            ],
        ];

        return $raw;
    }

    /**
     *
     * @param DeviceDetector $dd
     *
     * @return bool
     */
    private function hasResult(DeviceDetector $dd)
    {
        if ($dd->isBot() === true) {
            return true;
        }

        $client = $dd->getClient();
        if (isset($client['name']) && $this->isRealResult($client['name'])) {
            return true;
        }

        $os = $dd->getOs();
        if (isset($os['name']) && $this->isRealResult($os['name'])) {
            return true;
        }

        if ($dd->getDevice() !== null) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Model\Bot     $bot
     * @param array|boolean $botRaw
     */
    private function hydrateBot(Model\Bot $bot, $botRaw)
    {
        $bot->setIsBot(true);

        if (isset($botRaw['name']) && $this->isRealResult($botRaw['name'], 'bot', 'name')) {
            $bot->setName($botRaw['name']);
        }
        if (isset($botRaw['category']) && $this->isRealResult($botRaw['category'])) {
            $bot->setType($botRaw['category']);
        }
    }

    /**
     *
     * @param Model\Browser $browser
     * @param array|string  $clientRaw
     */
    private function hydrateBrowser(Model\Browser $browser, $clientRaw)
    {
        if (isset($clientRaw['name']) && $this->isRealResult($clientRaw['name']) === true) {
            $browser->setName($clientRaw['name']);
        }

        if (isset($clientRaw['version']) && $this->isRealResult($clientRaw['version']) === true) {
            $browser->getVersion()->setComplete($clientRaw['version']);
        }
    }

    /**
     *
     * @param Model\RenderingEngine $engine
     * @param array|string          $clientRaw
     */
    private function hydrateRenderingEngine(Model\RenderingEngine $engine, $clientRaw)
    {
        if (isset($clientRaw['engine']) && $this->isRealResult($clientRaw['engine']) === true) {
            $engine->setName($clientRaw['engine']);
        }
    }

    /**
     *
     * @param Model\OperatingSystem $os
     * @param array|string          $osRaw
     */
    private function hydrateOperatingSystem(Model\OperatingSystem $os, $osRaw)
    {
        if (isset($osRaw['name']) && $this->isRealResult($osRaw['name']) === true) {
            $os->setName($osRaw['name']);
        }

        if (isset($osRaw['version']) && $this->isRealResult($osRaw['version']) === true) {
            $os->getVersion()->setComplete($osRaw['version']);
        }
    }

    /**
     *
     * @param Model\UserAgent $device
     * @param DeviceDetector  $dd
     */
    private function hydrateDevice(Model\Device $device, DeviceDetector $dd)
    {
        if ($this->isRealResult($dd->getModel()) === true) {
            $device->setModel($dd->getModel());
        }

        if ($this->isRealResult($dd->getBrandName()) === true) {
            $device->setBrand($dd->getBrandName());
        }

        if ($this->isRealResult($dd->getDeviceName()) === true) {
            $device->setType($dd->getDeviceName());
        }

        if ($dd->isMobile() === true) {
            $device->setIsMobile(true);
        }

        if ($dd->isTouchEnabled() === true) {
            $device->setIsTouch(true);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $dd = $this->getParser();

        $dd->setUserAgent($userAgent);
        $dd->parse();

        /*
         * No result found?
         */
        if ($this->hasResult($dd) !== true) {
            throw new NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        /*
         * Hydrate the model
         */
        $result = new Model\UserAgent();
        $result->setProviderResultRaw($this->getResultRaw($dd));

        /*
         * Bot detection
         */
        if ($dd->isBot() === true) {
            $this->hydrateBot($result->getBot(), $dd->getBot());

            return $result;
        }

        /*
         * hydrate the result
         */
        $this->hydrateBrowser($result->getBrowser(), $dd->getClient());
        $this->hydrateRenderingEngine($result->getRenderingEngine(), $dd->getClient());
        $this->hydrateOperatingSystem($result->getOperatingSystem(), $dd->getOs());
        $this->hydrateDevice($result->getDevice(), $dd);

        return $result;
    }
}
