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

use Imagine\Filter\Basic\Show;
use Imagine\Test\Filter\FilterTestCase;

class ShowTest extends FilterTestCase
{
    public function testShouldShowImageAndReturnResult()
    {
        $image   = $this->getImage();
        $format  = 'jpg';
        $command = new Show($format);

        $image->expects($this->once())
            ->method('show')
            ->with($format)
            ->will($this->returnValue($image));

        $this->assertSame($image, $command->apply($image));
    }
}
