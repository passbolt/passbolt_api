<?php

namespace Gaufrette;

/**
 * Associates filesystem instances to domains
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class FilesystemMap
{
    private $filesystems = array();

    /**
     * Returns an array of all the registered filesystems where the key is the
     * domain and the value the filesystem
     *
     * @return array
     */
    public function all()
    {
        return $this->filesystems;
    }

    /**
     * Register the given filesystem for the specified domain
     *
     * @param string     $domain
     * @param Filesystem $filesystem
     *
     * @throws InvalidArgumentException when the specified domain contains
     *                                  forbidden characters
     */
    public function set($domain, Filesystem $filesystem)
    {
        if (! preg_match('/^[-_a-zA-Z0-9]+$/', $domain)) {
            throw new \InvalidArgumentException(sprintf(
                'The specified domain "%s" is not a valid domain.',
                $domain
            ));
        }

        $this->filesystems[$domain] = $filesystem;
    }

    /**
     * Indicates whether there is a filesystem registered for the specified
     * domain
     *
     * @param string $domain
     *
     * @return Boolean
     */
    public function has($domain)
    {
        return isset($this->filesystems[$domain]);
    }

    /**
     * Returns the filesystem registered for the specified domain
     *
     * @param string $domain
     *
     * @return Filesystem
     *
     * @throw  InvalidArgumentException when there is no filesystem registered
     *                                  for the specified domain
     */
    public function get($domain)
    {
        if (! $this->has($domain)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no filesystem defined for the "%s" domain.',
                $domain
            ));
        }

        return $this->filesystems[$domain];
    }

    /**
     * Removes the filesystem registered for the specified domain
     *
     * @param string $domain
     *
     * @return void
     */
    public function remove($domain)
    {
        if (! $this->has($domain)) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot remove the "%s" filesystem as it is not defined.',
                $domain
            ));
        }

        unset($this->filesystems[$domain]);
    }

    /**
     * Clears all the registered filesystems
     *
     * @return void
     */
    public function clear()
    {
        $this->filesystems = array();
    }
}
