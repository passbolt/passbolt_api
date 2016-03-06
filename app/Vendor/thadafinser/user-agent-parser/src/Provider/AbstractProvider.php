<?php
namespace UserAgentParser\Provider;

use DateTime;
use DateTimeZone;
use UserAgentParser\Exception;
use UserAgentParser\Model;

abstract class AbstractProvider
{
    /**
     * Name of the provider
     *
     * @var string
     */
    protected $name;

    /**
     * Homepage of the provider
     *
     * @var string
     */
    protected $homepage;

    /**
     * Composer package name
     *
     * @var string
     */
    protected $packageName;

    /**
     * Version string for caching
     *
     * @var string
     */
    private $version;

    /**
     * Last update date
     *
     * @var DateTime
     */
    private $updateDate;

    /**
     * Per default the provider cannot detect anything
     * Activate them in $detectionCapabilities
     *
     * @var array
     */
    protected $allDetectionCapabilities = [
        'browser' => [
            'name'    => false,
            'version' => false,
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
            'type'     => false,
            'isMobile' => false,
            'isTouch'  => false,
        ],

        'bot' => [
            'isBot' => false,
            'name'  => false,
            'type'  => false,
        ],
    ];

    /**
     * Set this in each Provider implementation
     *
     * @var array
     */
    protected $detectionCapabilities = [];

    protected $defaultValues = [
        'general' => [],
    ];

    /**
     * Return the name of the provider
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the homepage
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Get the package name
     *
     * @return string null
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * Return the version of the provider
     *
     * @return string null
     */
    public function getVersion()
    {
        if ($this->version !== null) {
            return $this->version;
        }

        if ($this->getPackageName() === null) {
            return;
        }

        $packages = $this->getPackages();

        if ($packages === null) {
            return;
        }

        foreach ($packages as $package) {
            if ($package->name === $this->getPackageName()) {
                $this->version = $package->version;

                break;
            }
        }

        return $this->version;
    }

    /**
     * Get the last change date of the provider
     *
     * @return DateTime null
     */
    public function getUpdateDate()
    {
        if ($this->updateDate !== null) {
            return $this->updateDate;
        }

        if ($this->getPackageName() === null) {
            return;
        }

        $packages = $this->getPackages();

        if ($packages === null) {
            return;
        }

        foreach ($packages as $package) {
            if ($package->name === $this->getPackageName()) {
                $updateDate = new DateTime($package->time, new DateTimeZone('UTC'));

                $this->updateDate = $updateDate;

                break;
            }
        }

        return $this->updateDate;
    }

    /**
     *
     * @return array null
     */
    private function getPackages()
    {
        if (! file_exists('composer.lock')) {
            return;
        }

        $content = file_get_contents('composer.lock');
        if ($content === false || $content === '') {
            return;
        }

        $content = json_decode($content);

        return array_merge($content->packages, $content->{'packages-dev'});
    }

    /**
     * What kind of capabilities this provider can detect
     *
     * @return array
     */
    public function getDetectionCapabilities()
    {
        return array_merge($this->allDetectionCapabilities, $this->detectionCapabilities);
    }

    /**
     *
     * @param  mixed   $value
     * @param  string  $group
     * @param  string  $part
     * @return boolean
     */
    protected function isRealResult($value, $group = null, $part = null)
    {
        $value = (string) $value;
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        $regexes = $this->defaultValues['general'];

        if ($group !== null && $part !== null && isset($this->defaultValues[$group][$part])) {
            $regexes = array_merge($regexes, $this->defaultValues[$group][$part]);
        }

        foreach ($regexes as $regex) {
            if (preg_match($regex, $value) === 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Parse the given user agent and return a result if possible
     *
     * @param string $userAgent
     * @param array  $headers
     *
     * @throws Exception\NoResultFoundException
     *
     * @return Model\UserAgent
     */
    abstract public function parse($userAgent, array $headers = []);
}
