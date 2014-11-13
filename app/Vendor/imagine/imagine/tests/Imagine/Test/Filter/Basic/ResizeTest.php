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

use Imagine\Filter\Basic\Resize;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Test\Filter\FilterTestCase;

class ResizeTest extends FilterTestCase
{
    /**
     * @covers Imagine\Filter\Basic\Resize::apply
     *
     * @dataProvider getDataSet
     *
     * @param BoxInterface $size
     */
    public function testShouldResizeImageAndReturnResult(BoxInterface $size)
    {
        $image = $this->getImage();

        $image->expects($this->once())
            ->method('resize')
            ->with($size)
            ->will($this->returnValue($image));

        $command = new Resize($size);

        $this->assertSame($image, $command->apply($image));
    }

    /**
     * Data provider for testShouldResizeImageAndReturnResult
     *
     * @return array
     */
    public function getDataSet()
    {
        return array(
            array(new Box(50, 15)),
            array(new Box(300, 25)),
            array(new Box(123, 23)),
            array(new Box(45, 23))
        );
    }
}
