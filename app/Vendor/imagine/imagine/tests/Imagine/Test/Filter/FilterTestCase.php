<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Filter;

abstract class FilterTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getImage()
    {
        return $this->getMock('Imagine\\Image\\ImageInterface');
    }

    protected function getImagine()
    {
        return $this->getMock('Imagine\\Image\\ImagineInterface');
    }

    protected function getDrawer()
    {
        return $this->getMock('Imagine\\Draw\\DrawerInterface');
    }

    protected function getPalette()
    {
        return $this->getMock('Imagine\\Image\\Palette\\PaletteInterface');
    }

    protected function getColor()
    {
        return $this->getMock('Imagine\\Image\\Palette\\Color\\ColorInterface');
    }
}
