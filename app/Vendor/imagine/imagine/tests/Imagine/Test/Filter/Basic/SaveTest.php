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

use Imagine\Filter\Basic\Save;
use Imagine\Test\Filter\FilterTestCase;

class SaveTest extends FilterTestCase
{
    public function testShouldSaveImageAndReturnResult()
    {
        $image   = $this->getImage();
        $path    = '/path/to/image.jpg';
        $command = new Save($path);

        $image->expects($this->once())
            ->method('save')
            ->with($path)
            ->will($this->returnValue($image));

        $this->assertSame($image, $command->apply($image));
    }
}
