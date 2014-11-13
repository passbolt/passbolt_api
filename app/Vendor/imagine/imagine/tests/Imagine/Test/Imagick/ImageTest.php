<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Imagick;

use Imagine\Image\ImageInterface;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Imagick\Imagine;
use Imagine\Imagick\Image;
use Imagine\Image\Palette\CMYK;
use Imagine\Image\Palette\RGB;
use Imagine\Test\Image\AbstractImageTest;
use Imagine\Image\Box;

class ImageTest extends AbstractImageTest
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('Imagick')) {
            $this->markTestSkipped('Imagick is not installed');
        }
    }

    protected function tearDown()
    {
        if (class_exists('Imagick')) {
            $prop = new \ReflectionProperty('Imagine\Imagick\Image', 'supportsColorspaceConversion');
            $prop->setAccessible(true);
            $prop->setValue(null);
        }

        parent::tearDown();
    }

    protected function getImagine()
    {
        return new Imagine();
    }

    public function testImageResizeUsesProperMethodBasedOnInputAndOutputSizes()
    {
        $imagine = $this->getImagine();

        $image = $imagine->open('tests/Imagine/Fixtures/resize/210-design-19933.jpg');

        $image
            ->resize(new Box(1500, 750))
            ->save('tests/Imagine/Fixtures/resize/large.png')
        ;

        $image
            ->resize(new Box(100, 50))
            ->save('tests/Imagine/Fixtures/resize/small.png')
        ;

        unlink('tests/Imagine/Fixtures/resize/large.png');
        unlink('tests/Imagine/Fixtures/resize/small.png');
    }

    // Older imagemagick versions does not support colorspace conversion
    public function testOlderImageMagickDoesNotAffectColorspaceUsageOnConstruct()
    {
        $palette = new CMYK();
        $imagick = $this->getMockBuilder('\Imagick')
            ->disableOriginalConstructor()
            ->getMock();
        $imagick->expects($this->any())
            ->method('setColorspace')
            ->will($this->throwException(new \RuntimeException('Method not supported')));

        $prop = new \ReflectionProperty('Imagine\Imagick\Image', 'supportsColorspaceConversion');
        $prop->setAccessible(true);
        $prop->setValue(false);

        return new Image($imagick, $palette, new MetadataBag());
    }

    /**
     * @depends testOlderImageMagickDoesNotAffectColorspaceUsageOnConstruct
     * @expectedException Imagine\Exception\RuntimeException
     * @expectedExceptionMessage Your version of Imagick does not support colorspace conversions.
     */
    public function testOlderImageMagickDoesNotAffectColorspaceUsageOnPaletteChange($image)
    {
        $image->usePalette(new RGB());
    }

    protected function supportMultipleLayers()
    {
        return true;
    }

    protected function getImageResolution(ImageInterface $image)
    {
        return $image->getImagick()->getImageResolution();
    }
}
