<?php

namespace Gaufrette\Adapter\AzureBlobStorage;

use WindowsAzure\Common\ServicesBuilder;

/**
 * Basic implementation for a Blob proxy factory
 *
 * @author Luciano Mammino <lmammino@oryzone.com>
 */
class BlobProxyFactory implements BlobProxyFactoryInterface
{
    /**
     * @var string $connectionString
     */
    protected $connectionString;

    /**
     * Constructor
     *
     * @param string $connectionString
     */
    public function __construct($connectionString)
    {
        $this->connectionString = $connectionString;
    }

    /**
     * {@inheritDoc}
     */
    public function create()
    {
        return ServicesBuilder::getInstance()->createBlobService($this->connectionString);
    }
}
