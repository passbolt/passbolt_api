<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;

class GoogleCloudStorageSpec extends ObjectBehavior
{
    public function let(\Google_Service_Storage $service)
    {
        $this->beConstructedWith($service, 'bucketName');
    }

    public function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    public function it_supports_metadata()
    {
        $this->shouldHaveType('Gaufrette\Adapter\MetadataSupporter');
    }

    public function it_is_list_keys_aware()
    {
        $this->shouldHaveType('Gaufrette\Adapter\ListKeysAware');
    }
}
