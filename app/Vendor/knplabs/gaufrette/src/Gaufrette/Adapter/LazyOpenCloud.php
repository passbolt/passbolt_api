<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter\OpenStackCloudFiles\ObjectStoreFactoryInterface;

/**
 * LazyOpenCloud
 *
 * @author  Daniel Richter <nexyz9@gmail.com>
 */
class LazyOpenCloud extends OpenCloud
{
    /**
     * @var ObjectStoreFactoryInterface
     */
    protected $objectStoreFactory;

    /**
     * @param ObjectStoreFactoryInterface $objectStoreFactory
     * @param string $containerName
     * @param bool $createContainer
     */
    public function __construct(ObjectStoreFactoryInterface $objectStoreFactory, $containerName, $createContainer = false)
    {
        $this->objectStoreFactory = $objectStoreFactory;
        $this->containerName = $containerName;
        $this->createContainer = $createContainer;
    }

    /**
     * Override parent to lazy-load object store
     *
     * {@inheritdoc}
     */
    protected function getContainer()
    {
        if (!$this->objectStore) {
            $this->objectStore = $this->objectStoreFactory->getObjectStore();
        }

        return parent::getContainer();
    }
}
