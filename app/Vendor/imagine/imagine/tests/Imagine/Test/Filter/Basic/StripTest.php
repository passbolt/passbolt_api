<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Filter\Basic;

use Imagine\Filter\Basic\Strip;
use Imagine\Test\Filter\FilterTestCase;

class StripTest extends FilterTestCase
{
    public function testShouldStripImage()
    {
        $image  = $this->getImage();
        $filter = new Strip();

        $image->expects($this->once())
            ->method('strip')
            ->will($this->returnValue($image));

        $this->assertSame($image, $filter->apply($image));
    }
}
