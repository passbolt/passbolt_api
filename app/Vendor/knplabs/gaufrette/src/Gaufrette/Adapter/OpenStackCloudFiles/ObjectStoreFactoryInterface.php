<?php

namespace Gaufrette\Adapter\OpenStackCloudFiles;

use OpenCloud\ObjectStore\Service;

/**
 * ObjectStoreFactoryInterface
 *
 * @author Daniel Richter <nexyz9@gmail.com>
 */
interface ObjectStoreFactoryInterface
{
    /**
     * @return Service
     */
    public function getObjectStore();
}
