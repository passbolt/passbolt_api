<?php

namespace spec\Gaufrette\Adapter;

use OpenCloud\ObjectStore\Exception\ObjectNotFoundException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * LazyOpenCloudSpec
 *
 * @author Daniel Richter <nexyz9@gmail.com>
 */
class LazyOpenCloudSpec extends ObjectBehavior
{
    /**
     * @param \Gaufrette\Adapter\OpenStackCloudFiles\ObjectStoreFactoryInterface $objectStoreFactory
     */
    function let($objectStoreFactory)
    {
        $this->beConstructedWith($objectStoreFactory, 'test-container-name');
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    /**
     * @param \Gaufrette\Adapter\OpenStackCloudFiles\ObjectStoreFactoryInterface $objectStoreFactory
     * @param \OpenCloud\ObjectStore\Service $objectStore
     * @param \OpenCloud\ObjectStore\Resource\Container $container
     */
    function it_initializes_object_store($objectStoreFactory, $objectStore, $container)
    {
        $objectStoreFactory->getObjectStore()->shouldBeCalled()->willReturn($objectStore);
        $objectStore->getContainer("test-container-name")->shouldBeCalled()->willReturn($container);
        $container->getObject("test-file-name")->willThrow(new ObjectNotFoundException());

        $this->read("test-file-name");
    }
}
