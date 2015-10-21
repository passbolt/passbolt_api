<?php

namespace Gaufrette\Adapter;

use \AmazonS3 as AmazonClient;
use Gaufrette\Adapter;

/**
 * Amazon S3 adapter using the AWS SDK for PHP v1.x.
 *
 * See the AwsS3 adapter for using the AWS SDK for PHP v2.x.
 *
 * @package Gaufrette
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 * @author  Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class AmazonS3 implements Adapter,
                          MetadataSupporter
{
    protected $service;
    protected $bucket;
    protected $ensureBucket = false;
    protected $metadata;
    protected $options;

    public function __construct(AmazonClient $service, $bucket, $options = array())
    {
        $this->service = $service;
        $this->bucket  = $bucket;
        $this->options = array_replace_recursive(
            array('directory' => '', 'create' => false, 'region' => $service->hostname, 'acl' => AmazonClient::ACL_PUBLIC),
            $options
        );
    }

    /**
     * Set the acl used when writing files
     *
     * @param string $acl
     */
    public function setAcl($acl)
    {
        $this->options['acl'] = $acl;
    }

    /**
     * Get the acl used when writing files
     *
     * @return string
     */
    public function getAcl()
    {
        return $this->options['acl'];
    }

    /**
     * Set the base directory the user will have access to
     *
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->options['directory'] = $directory;
    }

    /**
     * Get the directory the user has access to
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->options['directory'];
    }

    /**
     * {@inheritDoc}
     */
    public function setMetadata($key, $metadata)
    {
        $path = $this->computePath($key);

        $this->metadata[$path] = $metadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata($key)
    {
        $path = $this->computePath($key);

        return isset($this->metadata[$path]) ? $this->metadata[$path] : array();
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        $this->ensureBucketExists();

        $response = $this->service->get_object(
            $this->bucket,
            $this->computePath($key),
            $this->getMetadata($key)
        );

        if (!$response->isOK()) {
            return false;
        }

        return $response->body;
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        $this->ensureBucketExists();

        $response = $this->service->copy_object(
            array( // source
                'bucket'   => $this->bucket,
                'filename' => $this->computePath($sourceKey)
            ),
            array( // target
                'bucket'   => $this->bucket,
                'filename' => $this->computePath($targetKey)
            ),
            $this->getMetadata($sourceKey)
        );

        return $response->isOK() && $this->delete($sourceKey);
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content)
    {
        $this->ensureBucketExists();

        $opt = array_replace_recursive(
            array('acl'  => $this->options['acl']),
            $this->getMetadata($key),
            array('body' => $content)
        );

        $response = $this->service->create_object(
            $this->bucket,
            $this->computePath($key),
            $opt
        );

        if (!$response->isOK()) {
            return false;
        };

        return intval($response->header["x-aws-requestheaders"]["Content-Length"]);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        $this->ensureBucketExists();

        return $this->service->if_object_exists(
            $this->bucket,
            $this->computePath($key)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        $this->ensureBucketExists();

        $response = $this->service->get_object_metadata(
            $this->bucket,
            $this->computePath($key),
            $this->getMetadata($key)
        );

        return isset($response['Headers']['last-modified']) ? strtotime($response['Headers']['last-modified']) : false;
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $this->ensureBucketExists();

        $list = $this->service->get_object_list($this->bucket);

        $keys = array();
        foreach ($list as $file) {
            if ('.' !== dirname($file)) {
                $keys[] = dirname($file);
            }
            $keys[] = $file;
        }
        sort($keys);

        return $keys;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $this->ensureBucketExists();

        $response = $this->service->delete_object(
            $this->bucket,
            $this->computePath($key),
            $this->getMetadata($key)
        );

        return $response->isOK();
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        if ($this->exists($key.'/')) {
            return true;
        }

        return false;
    }

    /**
     * Ensures the specified bucket exists. If the bucket does not exists
     * and the create parameter is set to true, it will try to create the
     * bucket
     *
     * @throws \RuntimeException if the bucket does not exists or could not be
     *                          created
     */
    private function ensureBucketExists()
    {
        if ($this->ensureBucket) {
            return;
        }

        if (isset($this->options['region'])) {
            $this->service->set_region($this->options['region']);
        }

        if ($this->service->if_bucket_exists($this->bucket)) {
            $this->ensureBucket = true;

            return;
        }

        if (!$this->options['create']) {
            throw new \RuntimeException(sprintf(
                'The configured bucket "%s" does not exist.',
                $this->bucket
            ));
        }

        $response = $this->service->create_bucket(
            $this->bucket,
            $this->options['region']
        );

        if (!$response->isOK()) {
            throw new \RuntimeException(sprintf(
                'Failed to create the configured bucket "%s".',
                $this->bucket
            ));
        }

        $this->ensureBucket = true;
    }

    /**
     * Computes the path for the specified key taking the bucket in account
     *
     * @param string $key The key for which to compute the path
     *
     * @return string
     */
    private function computePath($key)
    {
        $directory = $this->getDirectory();
        if (null === $directory || '' === $directory) {
            return $key;
        }

        return sprintf('%s/%s', $directory, $key);
    }
}
