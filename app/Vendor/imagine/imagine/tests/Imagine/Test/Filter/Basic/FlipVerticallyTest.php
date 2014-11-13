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

use Imagine\Filter\Basic\FlipVertically;
use Imagine\Test\Filter\FilterTestCase;

class FlipVerticallyTest extends FilterTestCase
{
    public function testShouldFlipImage()
    {
        $image  = $this->getImage();
        $filter = new FlipVertically();

        $image->expects($this->once())
            ->method('flipVertically')
            ->will($this->returnValue($image));

        $this->assertSame($image, $filter->apply($image));
    }
}
