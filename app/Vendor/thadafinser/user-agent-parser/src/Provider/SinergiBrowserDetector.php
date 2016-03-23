<?php
namespace UserAgentParser\Provider;

use Sinergi\BrowserDetector;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Exception\PackageNotLoadedException;
use UserAgentParser\Model;

class SinergiBrowserDetector extends AbstractProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'SinergiBrowserDetector';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'https://github.com/sinergi/php-browser-detector';

    /**
     * Composer package name
     *
     * @var string
     */
    protected $packageName = 'sinergi/browser-detector';

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
            'brand'    => false,
            'type'     => false,
            'isMobile' => true,
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

        'device' => [
            'model' => [
                '/^Windows Phone$/i',
            ],
        ],
    ];

    /**
     * Used for unitTests mocking
     *
     * @var BrowserDetector\Browser
     */
    private $browserParser;

    /**
     * Used for unitTests mocking
     *
     * @var BrowserDetector\Os
     */
    private $osParser;

    /**
     * Used for unitTests mocking
     *
     * @var BrowserDetector\Device
     */
    private $deviceParser;

    /**
     *
     * @throws PackageNotLoadedException
     */
    public function __construct()
    {
        if (! file_exists('vendor/' . $this->getPackageName() . '/composer.json')) {
            throw new PackageNotLoadedException('You need to install the package ' . $this->getPackageName() . ' to use this provider');
        }
    }

    /**
     *
     * @param  string                  $userAgent
     * @return BrowserDetector\Browser
     */
    public function getBrowserParser($userAgent)
    {
        if ($this->browserParser !== null) {
            return $this->browserParser;
        }

        return new BrowserDetector\Browser($userAgent);
    }

    /**
     *
     * @param  string             $userAgent
     * @return BrowserDetector\Os
     */
    public function getOperatingSystemParser($userAgent)
    {
        if ($this->osParser !== null) {
            return $this->osParser;
        }

        return new BrowserDetector\Os($userAgent);
    }

    /**
     *
     * @param  string                 $userAgent
     * @return BrowserDetector\Device
     */
    public function getDeviceParser($userAgent)
    {
        if ($this->deviceParser !== null) {
            return $this->deviceParser;
        }

        return new BrowserDetector\Device($userAgent);
    }

    /**
     *
     * @param BrowserDetector\Browser $browserRaw
     * @param BrowserDetector\Os      $osRaw
     * @param BrowserDetector\Device  $deviceRaw
     *
     * @return boolean
     */
    private function hasResult(BrowserDetector\Browser $browserRaw, BrowserDetector\Os $osRaw, BrowserDetector\Device $deviceRaw)
    {
        if ($this->isRealResult($browserRaw->getName())) {
            return true;
        }

        if ($this->isRealResult($osRaw->getName())) {
            return true;
        }

        if ($this->isRealResult($deviceRaw->getName(), 'device', 'model')) {
            return true;
        }

        if ($browserRaw->isRobot() === true) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Model\Browser           $browser
     * @param BrowserDetector\Browser $browserRaw
     */
    private function hydrateBrowser(Model\Browser $browser, BrowserDetector\Browser $browserRaw)
    {
        if ($this->isRealResult($browserRaw->getName()) === true) {
            $browser->setName($browserRaw->getName());
        }

        if ($this->isRealResult($browserRaw->getVersion()) === true) {
            $browser->getVersion()->setComplete($browserRaw->getVersion());
        }
    }

    /**
     *
     * @param Model\OperatingSystem $os
     * @param BrowserDetector\Os    $osRaw
     */
    private function hydrateOperatingSystem(Model\OperatingSystem $os, BrowserDetector\Os $osRaw)
    {
        if ($this->isRealResult($osRaw->getName()) === true) {
            $os->setName($osRaw->getName());
        }

        if ($this->isRealResult($osRaw->getVersion()) === true) {
            $os->getVersion()->setComplete($osRaw->getVersion());
        }
    }

    /**
     *
     * @param Model\UserAgent        $device
     * @param BrowserDetector\Os     $osRaw
     * @param BrowserDetector\Device $deviceRaw
     */
    private function hydrateDevice(Model\Device $device, BrowserDetector\Os $osRaw, BrowserDetector\Device $deviceRaw)
    {
        if ($this->isRealResult($deviceRaw->getName(), 'device', 'model') === true) {
            $device->setModel($deviceRaw->getName());
        }

        if ($osRaw->isMobile() === true) {
            $device->setIsMobile(true);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $browserRaw = $this->getBrowserParser($userAgent);
        $osRaw      = $this->getOperatingSystemParser($userAgent);
        $deviceRaw  = $this->getDeviceParser($userAgent);

        /*
         * No result found?
         */
        if ($this->hasResult($browserRaw, $osRaw, $deviceRaw) !== true) {
            throw new NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        /*
         * Hydrate the model
         */
        $result = new Model\UserAgent();
        $result->setProviderResultRaw([
            'browser'         => $browserRaw,
            'operatingSystem' => $osRaw,
            'device'          => $deviceRaw,
        ]);

        /*
         * Bot detection
         */
        if ($browserRaw->isRobot() === true) {
            $bot = $result->getBot();
            $bot->setIsBot(true);

            return $result;
        }

        /*
         * hydrate the result
         */
        $this->hydrateBrowser($result->getBrowser(), $browserRaw);
        // renderingEngine not available
        $this->hydrateOperatingSystem($result->getOperatingSystem(), $osRaw);
        $this->hydrateDevice($result->getDevice(), $osRaw, $deviceRaw);

        return $result;
    }
}
