<?php

namespace spec\Gaufrette\Util;

use PhpSpec\ObjectBehavior;

class SizeSpec extends ObjectBehavior
{
    function it_calculates_size_of_content()
    {
        $this->fromContent('some content')->shouldReturn(12);
        $this->fromContent('some other content')->shouldReturn(18);
        $this->fromContent('some')->shouldReturn(4);
    }
}
