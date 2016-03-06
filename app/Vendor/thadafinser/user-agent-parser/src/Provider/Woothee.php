<?php
namespace UserAgentParser\Provider;

use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Exception\PackageNotLoadedException;
use UserAgentParser\Model;
use Woothee\Classifier;
use Woothee\DataSet;

class Woothee extends AbstractProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name = 'Woothee';

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage = 'https://github.com/woothee/woothee-php';

    /**
     * Composer package name
     *
     * @var string
     */
    protected $packageName = 'woothee/woothee';

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
            'name'    => false,
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
            'name'  => true,
            'type'  => false,
        ],
    ];

    protected $defaultValues = [

        'general' => [
            '/^UNKNOWN$/i',
        ],

        'bot' => [
            'name' => [
                '/^misc crawler$/i',
            ],
        ],
    ];

    private $parser;

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
     * @return Classifier
     */
    public function getParser()
    {
        if ($this->parser !== null) {
            return $this->parser;
        }

        $this->parser = new Classifier();

        return $this->parser;
    }

    /**
     *
     * @param array $resultRaw
     *
     * @return bool
     */
    private function hasResult(array $resultRaw)
    {
        if (isset($resultRaw['category']) && $this->isRealResult($resultRaw['category'])) {
            return true;
        }

        if (isset($resultRaw['name']) && $this->isRealResult($resultRaw['name'])) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param  array   $resultRaw
     * @return boolean
     */
    private function isBot(array $resultRaw)
    {
        if (isset($resultRaw['category']) && $resultRaw['category'] === DataSet::DATASET_CATEGORY_CRAWLER) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param Model\Bot $bot
     * @param array     $resultRaw
     */
    private function hydrateBot(Model\Bot $bot, array $resultRaw)
    {
        $bot->setIsBot(true);

        if (isset($resultRaw['name']) && $this->isRealResult($resultRaw['name'], 'bot', 'name') === true) {
            $bot->setName($resultRaw['name']);
        }
    }

    /**
     *
     * @param Model\Browser $browser
     * @param array         $resultRaw
     */
    private function hydrateBrowser(Model\Browser $browser, array $resultRaw)
    {
        if (isset($resultRaw['name']) && $this->isRealResult($resultRaw['name']) === true) {
            $browser->setName($resultRaw['name']);
        }

        if (isset($resultRaw['version']) && $this->isRealResult($resultRaw['version']) === true) {
            $browser->getVersion()->setComplete($resultRaw['version']);
        }
    }

    /**
     *
     * @param Model\Device $device
     * @param array        $resultRaw
     */
    private function hydrateDevice(Model\Device $device, array $resultRaw)
    {
        if (isset($resultRaw['category']) && $this->isRealResult($resultRaw['category']) === true) {
            $device->setType($resultRaw['category']);
        }
    }

    public function parse($userAgent, array $headers = [])
    {
        $parser = $this->getParser();

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
        $this->hydrateBrowser($result->getBrowser(), $resultRaw);
        // renderingEngine not available
        // operatingSystem filled OS is mixed! Examples: iPod, iPhone, Android...
        $this->hydrateDevice($result->getDevice(), $resultRaw);

        return $result;
    }
}
