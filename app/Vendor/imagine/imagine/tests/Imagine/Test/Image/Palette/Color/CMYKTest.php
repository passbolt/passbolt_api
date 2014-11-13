<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Image\Palette\Color;

use Imagine\Image\Palette\Color\CMYK;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\CMYK as CMYKPalette;

class CMYKTest extends AbstractColorTest
{
    /**
     * @expectedException Imagine\Exception\RuntimeException
     */
    public function testDissolve()
    {
        $this->getColor()->dissolve(1);
    }

    public function provideOpaqueColors()
    {
        return array(
            array($this->getColor()),
        );
    }

    public function testIsNotOpaque($color = null)
    {
        $this->markTestSkipped('CMYK color can not be not opaque');
    }

    public function provideNotOpaqueColors()
    {
        $this->markTestSkipped('CMYK color can not be not opaque');
    }

    public function provideGrayscaleData()
    {
        return array(
            array('cmyk(42%, 42%, 42%, 25%)', $this->getColor()),
        );
    }

    public function provideColorAndAlphaTuples()
    {
        return array(
            array(null, $this->getColor())
        );
    }

    protected function getColor()
    {
        return new CMYK(new CMYKPalette(), array(12, 23, 45, 25));
    }

    public function provideColorAndValueComponents()
    {
        return array(
            array(array(
                ColorInterface::COLOR_CYAN => 12,
                ColorInterface::COLOR_MAGENTA => 23,
                ColorInterface::COLOR_YELLOW => 45,
                ColorInterface::COLOR_KEYLINE => 25,
            ), $this->getColor()),
        );
    }
}
