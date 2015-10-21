<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Gaufrette\Util;
use Gaufrette\Adapter\AzureBlobStorage\BlobProxyFactoryInterface;

use WindowsAzure\Blob\Models\CreateBlobOptions;
use WindowsAzure\Blob\Models\CreateContainerOptions;
use WindowsAzure\Blob\Models\DeleteContainerOptions;
use WindowsAzure\Blob\Models\ListBlobsOptions;
use WindowsAzure\Common\ServiceException;

/**
 * Microsoft Azure Blob Storage adapter
 *
 * @author Luciano Mammino <lmammino@oryzone.com>
 * @author Paweł Czyżewski <pawel.czyzewski@enginewerk.com>
 */
class AzureBlobStorage implements Adapter,
                                  MetadataSupporter
{
    /**
     * Error constants
     */
    const ERROR_CONTAINER_ALREADY_EXISTS = 'ContainerAlreadyExists';
    const ERROR_CONTAINER_NOT_FOUND = 'ContainerNotFound';

    /**
     * @var AzureBlobStorage\BlobProxyFactoryInterface $blobProxyFactory
     */
    protected $blobProxyFactory;

    /**
     * @var string $containerName
     */
    protected $containerName;

    /**
     * @var bool $detectContentType
     */
    protected $detectContentType;

    /**
     * @var \WindowsAzure\Blob\Internal\IBlob $blobProxy
     */
    protected $blobProxy;

    /**
     * Constructor
     *
     * @param AzureBlobStorage\BlobProxyFactoryInterface $blobProxyFactory
     * @param string                                     $containerName
     * @param bool                                       $create
     * @param bool                                       $detectContentType
     */
    public function __construct(BlobProxyFactoryInterface $blobProxyFactory, $containerName, $create = false, $detectContentType = true)
    {
        $this->blobProxyFactory = $blobProxyFactory;
        $this->containerName = $containerName;
        $this->detectContentType = $detectContentType;
        if ($create) {
            $this->createContainer($containerName);
        }
    }

    /**
     * Creates a new container
     *
     * @param  string                                           $containerName
     * @param  \WindowsAzure\Blob\Models\CreateContainerOptions $options
     * @throws \RuntimeException                                if cannot create the container
     */
    public function createContainer($containerName, CreateContainerOptions $options = null)
    {
        $this->init();

        try {
            $this->blobProxy->createContainer($containerName, $options);
        } catch (ServiceException $e) {
            $errorCode = $this->getErrorCodeFromServiceException($e);

            if ($errorCode != self::ERROR_CONTAINER_ALREADY_EXISTS) {
                throw new \RuntimeException(sprintf(
                    'Failed to create the configured container "%s": %s (%s).',
                    $containerName,
                    $e->getErrorText(),
                    $errorCode
                ));
            }
        }
    }

    /**
     * Deletes a container
     *
     * @param  string                 $containerName
     * @param  DeleteContainerOptions $options
     * @throws \RuntimeException      if cannot delete the container
     */
    public function deleteContainer($containerName, DeleteContainerOptions $options = null)
    {
        $this->init();

        try {
            $this->blobProxy->deleteContainer($containerName, $options);
        } catch (ServiceException $e) {
            $errorCode = $this->getErrorCodeFromServiceException($e);

            if ($errorCode != self::ERROR_CONTAINER_NOT_FOUND) {
                throw new \RuntimeException(sprintf(
                    'Failed to delete the configured container "%s": %s (%s).',
                    $containerName,
                    $e->getErrorText(),
                    $errorCode
                ), $e->getCode());
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        $this->init();

        try {
            $blob = $this->blobProxy->getBlob($this->containerName, $key);

            return stream_get_contents($blob->getContentStream());
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, sprintf('read key "%s"', $key));

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content)
    {
        $this->init();

        try {
            $options = new CreateBlobOptions();

            if ($this->detectContentType) {
                $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
                $contentType = $fileInfo->buffer($content);
                $options->setContentType($contentType);
            }

            $this->blobProxy->createBlockBlob($this->containerName, $key, $content, $options);

            return Util\Size::fromContent($content);
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, sprintf('write content for key "%s"', $key));

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        $this->init();

        $listBlobsOptions = new ListBlobsOptions();
        $listBlobsOptions->setPrefix($key);

        try {
            $blobsList = $this->blobProxy->listBlobs($this->containerName, $listBlobsOptions);

            foreach ($blobsList->getBlobs() as $blob) {
                if ($key === $blob->getName()) {
                    return true;
                }
            }
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, 'check if key exists');
            $errorCode = $this->getErrorCodeFromServiceException($e);

            throw new \RuntimeException(sprintf(
                'Failed to check if key "%s" exists in container "%s": %s (%s).',
                $key,
                $this->containerName,
                $e->getErrorText(),
                $errorCode
            ), $e->getCode());
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $this->init();

        try {
            $blobList = $this->blobProxy->listBlobs($this->containerName);

            return array_map(
                function($blob) {
                    return $blob->getName();
                },
                $blobList->getBlobs()
            );
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, 'retrieve keys');
            $errorCode = $this->getErrorCodeFromServiceException($e);

            throw new \RuntimeException(sprintf(
                'Failed to list keys for the container "%s": %s (%s).',
                $this->containerName,
                $e->getErrorText(),
                $errorCode
            ), $e->getCode());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        $this->init();

        try {
            $properties = $this->blobProxy->getBlobProperties($this->containerName, $key);

            return $properties->getProperties()->getLastModified()->getTimestamp();
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, sprintf('read mtime for key "%s"', $key));

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $this->init();

        try {
            $this->blobProxy->deleteBlob($this->containerName, $key);

            return true;
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, sprintf('delete key "%s"', $key));

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        $this->init();

        try {
            $this->blobProxy->copyBlob($this->containerName, $targetKey, $this->containerName, $sourceKey);
            $this->blobProxy->deleteBlob($this->containerName, $sourceKey);

            return true;
        } catch (ServiceException $e) {
            $this->failIfContainerNotFound($e, sprintf('rename key "%s"', $sourceKey));

            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        // Windows Azure Blob Storage does not support directories
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function setMetadata($key, $content)
    {
        $this->init();

        try {
            $this->blobProxy->setBlobMetadata($this->containerName, $key, $content);
        } catch (ServiceException $e) {
            $errorCode = $this->getErrorCodeFromServiceException($e);

            throw new \RuntimeException(sprintf(
                'Failed to set metadata for blob "%s" in container "%s": %s (%s).',
                $key,
                $this->containerName,
                $e->getErrorText(),
                $errorCode
            ), $e->getCode());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata($key)
    {
        $this->init();

        try {
            $properties = $this->blobProxy->getBlobProperties($this->containerName, $key);

            return $properties->getMetadata();
        } catch (ServiceException $e) {
            $errorCode = $this->getErrorCodeFromServiceException($e);

            throw new \RuntimeException(sprintf(
                'Failed to get metadata for blob "%s" in container "%s": %s (%s).',
                $key,
                $this->containerName,
                $e->getErrorText(),
                $errorCode
            ), $e->getCode());
        }
    }

    /**
     * Lazy initialization, automatically called when some method is called after construction
     */
    protected function init()
    {
        if ($this->blobProxy == null) {
            $this->blobProxy = $this->blobProxyFactory->create();
        }
    }

    /**
     * Throws a runtime exception if a give ServiceException derived from a "container not found" error
     *
     * @param  ServiceException  $exception
     * @param  string            $action
     * @throws \RuntimeException
     */
    protected function failIfContainerNotFound(ServiceException $exception, $action)
    {
        $errorCode = $this->getErrorCodeFromServiceException($exception);

        if ($errorCode == self::ERROR_CONTAINER_NOT_FOUND) {
            throw new \RuntimeException(sprintf(
                'Failed to %s: container "%s" not found.',
                $action,
                $this->containerName
            ), $exception->getCode());
        }
    }

    /**
     * Extracts the error code from a service exception
     *
     * @param  ServiceException $exception
     * @return string
     */
    protected function getErrorCodeFromServiceException(ServiceException $exception)
    {
        $xml = @simplexml_load_string($exception->getErrorReason());

        if ($xml && isset($xml->Code)) {
            return (string) $xml->Code;
        }

        return $exception->getErrorReason();
    }
}
