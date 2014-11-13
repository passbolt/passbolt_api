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

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Filter\Basic\Crop;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use Imagine\Test\Filter\FilterTestCase;

class CropTest extends FilterTestCase
{
    /**
     * @covers Imagine\Filter\Basic\Crop::apply
     *
     * @dataProvider getDataSet
     *
     * @param PointInterface $start
     * @param BoxInterface   $size
     */
    public function testShouldApplyCropAndReturnResult(PointInterface $start, BoxInterface $size)
    {
        $image = $this->getImage();

        $command = new Crop($start, $size);

        $image->expects($this->once())
            ->method('crop')
            ->with($start, $size)
            ->will($this->returnValue($image));

        $this->assertSame($image, $command->apply($image));
    }

    /**
     * Provides coordinates and sizes for testShouldApplyCropAndReturnResult
     *
     * @return array
     */
    public function getDataSet()
    {
        return array(
            array(new Point(0, 0), new Box(40, 50)),
            array(new Point(0, 15), new Box(50, 32))
        );
    }
}
