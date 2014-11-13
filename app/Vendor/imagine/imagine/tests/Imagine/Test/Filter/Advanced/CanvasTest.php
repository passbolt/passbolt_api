<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Filter\Advanced;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Filter\Advanced\Canvas;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use Imagine\Test\Filter\FilterTestCase;

class CanvasTest extends FilterTestCase
{
    /**
     * @covers Imagine\Filter\Advanced\Canvas::apply
     *
     * @dataProvider getDataSet
     *
     * @param BoxInterface   $size
     * @param PointInterface $placement
     * @param ColorInterface $background
     */
    public function testShouldCanvasImageAndReturnResult(BoxInterface $size, PointInterface $placement = null, ColorInterface $background = null)
    {
        $placement = $placement ?: new Point(0, 0);
        $image = $this->getImage();

        $canvas = $this->getImage();
        $canvas->expects($this->once())->method('paste')->with($image, $placement);

        $imagine = $this->getImagine();
        $imagine->expects($this->once())->method('create')->with($size, $background)->will($this->returnValue($canvas));

        $command = new Canvas($imagine, $size, $placement, $background);

        $this->assertSame($canvas, $command->apply($image));
    }

    /**
     * Data provider for testShouldCanvasImageAndReturnResult
     *
     * @return array
     */
    public function getDataSet()
    {
        return array(
            array(new Box(50, 15), new Point(10, 10), $this->getColor()),
            array(new Box(300, 25), new Point(15, 15)),
            array(new Box(123, 23)),
        );
    }
}
