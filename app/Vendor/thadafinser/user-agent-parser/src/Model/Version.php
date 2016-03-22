<?php
namespace UserAgentParser\Model;

class Version
{
    /**
     *
     * @var integer
     */
    private $major;

    /**
     *
     * @var integer
     */
    private $minor;

    /**
     *
     * @var integer
     */
    private $patch;

    /**
     *
     * @var string
     */
    private $alias;

    /**
     *
     * @var string
     */
    private $complete;

    private static $notAllowedAlias = [
        'a',
        'alpha',
        'prealpha',

        'b',
        'beta',
        'prebeta',

        'rc',
    ];

    /**
     *
     * @param integer $major
     */
    public function setMajor($major)
    {
        if ($major !== null) {
            $major = (int) $major;
        }

        $this->major = $major;

        $this->hydrateComplete();
    }

    /**
     *
     * @return integer
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     *
     * @param integer $minor
     */
    public function setMinor($minor)
    {
        if ($minor !== null) {
            $minor = (int) $minor;
        }

        $this->minor = $minor;

        $this->hydrateComplete();
    }

    /**
     *
     * @return integer
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     *
     * @param integer $patch
     */
    public function setPatch($patch)
    {
        if ($patch !== null) {
            $patch = (int) $patch;
        }

        $this->patch = $patch;

        $this->hydrateComplete();
    }

    /**
     *
     * @return integer
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     *
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        $this->hydrateComplete();
    }

    /**
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set from the complete version string.
     *
     * @param string $complete
     */
    public function setComplete($complete)
    {
        // check if the version has only 0 -> so no real result
        // maybe move this out to the Providers itself?
        $left = preg_replace('/[0.]/', '', $complete);
        if ($left === '') {
            $complete = null;
        }

        $this->hydrateFromComplete($complete);

        $this->complete = $complete;
    }

    /**
     *
     * @return string
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'major' => $this->getMajor(),
            'minor' => $this->getMinor(),
            'patch' => $this->getPatch(),

            'alias' => $this->getAlias(),

            'complete' => $this->getComplete(),
        ];
    }

    /**
     *
     * @return string
     */
    private function hydrateComplete()
    {
        if ($this->getMajor() === null && $this->getAlias() === null) {
            return;
        }

        $version = $this->getMajor();

        if ($this->getMinor() !== null) {
            $version .= '.' . $this->getMinor();
        }

        if ($this->getPatch() !== null) {
            $version .= '.' . $this->getPatch();
        }

        if ($this->getAlias() !== null) {
            $version = $this->getAlias() . ' - ' . $version;
        }

        $this->complete = $version;
    }

    private function hydrateFromComplete($complete)
    {
        $parts = $this->getCompleteParts($complete);

        $this->setMajor($parts['major']);
        $this->setMinor($parts['minor']);
        $this->setPatch($parts['patch']);
        $this->setAlias($parts['alias']);
    }

    /**
     *
     * @return array
     */
    private function getCompleteParts($complete)
    {
        $versionParts = [
            'major' => null,
            'minor' => null,
            'patch' => null,

            'alias' => null,
        ];

        // only digits
        preg_match("/\d+(?:\.*\d*)*/", $complete, $result);
        if (count($result) > 0) {
            $parts = explode('.', $result[0]);

            if (isset($parts[0]) && $parts[0] != '') {
                $versionParts['major'] = (int) $parts[0];
            }
            if (isset($parts[1]) && $parts[1] != '') {
                $versionParts['minor'] = (int) $parts[1];
            }
            if (isset($parts[2]) && $parts[2] != '') {
                $versionParts['patch'] = (int) $parts[2];
            }
        }

        // grab alias
        $result = preg_split("/\d+(?:\.*\d*)*/", $complete);
        foreach ($result as $row) {
            $row = trim($row);

            if ($row === '') {
                continue;
            }

            // do not use beta and other things
            if (in_array($row, self::$notAllowedAlias)) {
                continue;
            }

            $versionParts['alias'] = $row;
        }

        return $versionParts;
    }
}
