<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Gaufrette\Util;
use Gaufrette\Exception;
use \Dropbox_API as DropboxApi;
use \Dropbox_Exception_NotFound as DropboxNotFoundException;

/**
 * Dropbox adapter
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 * @author Antoine Hérault <antoine.herault@gmail.com>
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class Dropbox implements Adapter
{
    protected $client;

    /**
     * Constructor
     *
     * @param \Dropbox_API $client The Dropbox API client
     */
    public function __construct(DropboxApi $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Dropbox_Exception_Forbidden
     * @throws \Dropbox_Exception_OverQuota
     * @throws \OAuthException
     */
    public function read($key)
    {
        try {
            return $this->client->getFile($key);
        } catch (DropboxNotFoundException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        try {
            $metadata = $this->getDropboxMetadata($key);
        } catch (Exception\FileNotFound $e) {
            return false;
        }

        return (boolean) isset($metadata['is_dir']) ? $metadata['is_dir'] : false;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Dropbox_Exception
     */
    public function write($key, $content)
    {
        $resource = tmpfile();
        fwrite($resource, $content);
        fseek($resource, 0);

        try {
            $this->client->putFile($key, $resource);
        } catch (\Exception $e) {
            fclose($resource);

            throw $e;
        }

        fclose($resource);

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        try {
            $this->client->delete($key);
        } catch (DropboxNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        try {
            $this->client->move($sourceKey, $targetKey);
        } catch (DropboxNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        try {
            $metadata = $this->getDropboxMetadata($key);
        } catch (Exception\FileNotFound $e) {
            return false;
        }

        return strtotime($metadata['modified']);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $metadata = $this->client->getMetaData('/', true);
        if (! isset($metadata['contents'])) {
            return array();
        }

        $keys = array();
        foreach ($metadata['contents'] as $value) {
            $file = ltrim($value['path'], '/');
            $keys[] = $file;
            if ('.' !== dirname($file)) {
                $keys[] = dirname($file);
            }
        }
        sort($keys);

        return $keys;
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        try {
            $this->getDropboxMetadata($key);

            return true;
        } catch (Exception\FileNotFound $e) {
            return false;
        }
    }

    private function getDropboxMetadata($key)
    {
        try {
            $metadata = $this->client->getMetaData($key, false);
        } catch (DropboxNotFoundException $e) {
            throw new Exception\FileNotFound($key, 0, $e);
        }

        // TODO find a way to exclude deleted files
        if (isset($metadata['is_deleted']) && $metadata['is_deleted']) {
            throw new Exception\FileNotFound($key);
        }

        return $metadata;
    }
}
