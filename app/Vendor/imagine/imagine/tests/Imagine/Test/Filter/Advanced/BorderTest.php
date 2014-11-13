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

use Imagine\Filter\Advanced\Border;
use Imagine\Test\Filter\FilterTestCase;

class BorderTest extends FilterTestCase
{
    public function testBorderImage()
    {
        $color       = $this->getMock('Imagine\\Image\\Palette\\Color\\ColorInterface');
        $width       = 2;
        $height      = 4;
        $image       = $this->getImage();
        $imageWidth  = 200;
        $imageHeight = 100;

        $size = $this->getMock('Imagine\\Image\\BoxInterface');
        $size->expects($this->once())
             ->method('getWidth')
             ->will($this->returnValue($width));

        $size->expects($this->once())
             ->method('getHeight')
             ->will($this->returnValue($height));

        $draw = $this->getDrawer();
        $draw->expects($this->exactly(4))
             ->method('line')
             ->will($this->returnValue($draw));

        $image->expects($this->once())
              ->method('getSize')
              ->will($this->returnValue($size));

        $image->expects($this->once())
              ->method('draw')
              ->will($this->returnValue($draw));

        $filter = new Border($color, $width, $height);

        $this->assertSame($image, $filter->apply($image));
    }
}
