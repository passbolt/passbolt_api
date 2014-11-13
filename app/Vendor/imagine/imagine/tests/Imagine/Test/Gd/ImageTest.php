<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Gd;

use Imagine\Gd\Imagine;
use Imagine\Test\Image\AbstractImageTest;
use Imagine\Image\ImageInterface;
use Imagine\Exception\RuntimeException;

class ImageTest extends AbstractImageTest
{
    protected function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info')) {
            $this->markTestSkipped('Gd not installed');
        }
    }

    public function testImageResolutionChange()
    {
        $this->markTestSkipped('GD driver does not support resolution options');
    }

    public function provideFilters()
    {
        return array(
            array(ImageInterface::FILTER_UNDEFINED),
        );
    }

    public function providePalettes()
    {
        return array(
            array('Imagine\Image\Palette\RGB', array(255, 0, 0)),
        );
    }

    public function provideFromAndToPalettes()
    {
        return array(
            array(
                'Imagine\Image\Palette\RGB',
                'Imagine\Image\Palette\RGB',
                array(10, 10, 10),
            ),
        );
    }

    public function testProfile()
    {
        try {
            parent::testProfile();
            $this->fail('A RuntimeException should have been raised');
        } catch (RuntimeException $e) {

        }
    }

    public function testPaletteIsGrayIfGrayImage()
    {
        $this->markTestSkipped('Gd does not support Gray colorspace');
    }

    public function testPaletteIsCMYKIfCMYKImage()
    {
        $this->markTestSkipped('GD driver does not recognize CMYK images properly');
    }

    public function testGetColorAtCMYK()
    {
        $this->markTestSkipped('GD driver does not recognize CMYK images properly');
    }

    public function testChangeColorSpaceAndStripImage()
    {
        $this->markTestSkipped('GD driver does not support ICC profiles');
    }

    public function testStripGBRImageHasGoodColors()
    {
        $this->markTestSkipped('GD driver does not support ICC profiles');
    }

    protected function getImagine()
    {
        return new Imagine();
    }

    protected function supportMultipleLayers()
    {
        return false;
    }

    public function testRotateWithNoBackgroundColor()
    {
        if (version_compare(PHP_VERSION, '5.5', '>=')) {
            // see https://bugs.php.net/bug.php?id=65148
            $this->markTestSkipped('Disabling test while bug #65148 is open');
        }

        parent::testRotateWithNoBackgroundColor();
    }

    /**
     * @dataProvider provideVariousSources
     */
    public function testResolutionOnSave($source)
    {
        $this->markTestSkipped('Gd only supports 72 dpi resolution');
    }

    protected function getImageResolution(ImageInterface $image)
    {
    }
}
