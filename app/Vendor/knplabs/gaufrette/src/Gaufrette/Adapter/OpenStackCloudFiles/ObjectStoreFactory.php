<?php

namespace Gaufrette\Adapter\OpenStackCloudFiles;

use OpenCloud\OpenStack;

/**
 * ObjectStoreFactory
 *
 * @author Daniel Richter <nexyz9@gmail.com>
 */
class ObjectStoreFactory implements ObjectStoreFactoryInterface
{
    /**
     * @var OpenStack
     */
    protected $connection;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $urlType;

    /**
     * @var string
     */
    protected $objectStoreType;

    /**
     * Constructor
     *
     * @param OpenStack $connection
     * @param string $region
     * @param string $urlType
     * @param string $objectStoreType
     */
    public function __construct(OpenStack $connection, $region, $urlType, $objectStoreType = 'cloudFiles')
    {
        $this->connection = $connection;
        $this->region = $region;
        $this->urlType = $urlType;
        $this->objectStoreType = $objectStoreType;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectStore()
    {
        return $this->connection->objectStoreService($this->objectStoreType, $this->region, $this->urlType);
    }
}
