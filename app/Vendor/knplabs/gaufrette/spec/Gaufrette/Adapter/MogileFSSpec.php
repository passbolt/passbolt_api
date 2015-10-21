<?php

namespace spec\Gaufrette\Adapter;

use PhpSpec\ObjectBehavior;

class MogileFSSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('http://domain.com', array('localhost'));
    }

    function it_is_adapter()
    {
        $this->shouldHaveType('Gaufrette\Adapter');
    }

    function it_does_not_handle_mtime()
    {
        $this->mtime('filename')->shouldReturn(false);
        $this->mtime('filename2')->shouldReturn(false);
    }
}
