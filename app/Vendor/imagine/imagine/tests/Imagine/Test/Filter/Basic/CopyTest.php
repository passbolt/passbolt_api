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

use Imagine\Test\Filter\FilterTestCase;
use Imagine\Filter\Basic\Copy;

class CopyTest extends FilterTestCase
{
    public function testShouldCopyAndReturnResultingImage()
    {
        $command = new Copy();
        $image   = $this->getImage();
        $clone   = $this->getImage();

        $image->expects($this->once())
            ->method('copy')
            ->will($this->returnValue($clone));

        $this->assertSame($clone, $command->apply($image));
    }
}
