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

use Imagine\Filter\Basic\WebOptimization;
use Imagine\Image\ImageInterface;
use Imagine\Test\Filter\FilterTestCase;

class WebOptimizationTest extends FilterTestCase
{
    public function testShouldNotSave()
    {
        $image     = $this->getImage();
        $filter    = new WebOptimization();

        $image->expects($this->once())
            ->method('usePalette')
            ->with($this->isInstanceOf('Imagine\Image\Palette\RGB'))
            ->will($this->returnValue($image));

        $image->expects($this->once())
            ->method('strip')
            ->will($this->returnValue($image));

        $image->expects($this->never())
            ->method('save');

        $this->assertSame($image, $filter->apply($image));
    }

    public function testShouldSaveWithCallbackAndCustomOption()
    {
        $image     = $this->getImage();
        $result    = '/path/to/ploum';
        $path      = function (ImageInterface $image) use ($result) { return $result; };
        $filter    = new WebOptimization($path, array(
            'custom-option' => 'custom-value',
            'resolution-y'  => 100,
        ));
        $capturedOptions = null;

        $image->expects($this->once())
            ->method('usePalette')
            ->with($this->isInstanceOf('Imagine\Image\Palette\RGB'))
            ->will($this->returnValue($image));

        $image->expects($this->once())
            ->method('strip')
            ->will($this->returnValue($image));

        $image->expects($this->once())
            ->method('save')
            ->with($this->equalTo($result), $this->isType('array'))
            ->will($this->returnCallback(function ($path, $options) use (&$capturedOptions, $image) {
                $capturedOptions = $options;

                return $image;
            }));

        $this->assertSame($image, $filter->apply($image));

        $this->assertCount(4, $capturedOptions);
        $this->assertEquals('custom-value', $capturedOptions['custom-option']);
        $this->assertEquals(ImageInterface::RESOLUTION_PIXELSPERINCH, $capturedOptions['resolution-units']);
        $this->assertEquals(72, $capturedOptions['resolution-x']);
        $this->assertEquals(100, $capturedOptions['resolution-y']);
    }

    public function testShouldSaveWithPathAndCustomOption()
    {
        $image     = $this->getImage();
        $path      = '/path/to/dest';
        $filter    = new WebOptimization($path, array(
            'custom-option' => 'custom-value',
            'resolution-y'  => 100,
        ));
        $capturedOptions = null;

        $image->expects($this->once())
            ->method('usePalette')
            ->with($this->isInstanceOf('Imagine\Image\Palette\RGB'))
            ->will($this->returnValue($image));

        $image->expects($this->once())
            ->method('strip')
            ->will($this->returnValue($image));

        $image->expects($this->once())
            ->method('save')
            ->with($this->equalTo($path), $this->isType('array'))
            ->will($this->returnCallback(function ($path, $options) use (&$capturedOptions, $image) {
                $capturedOptions = $options;

                return $image;
            }));

        $this->assertSame($image, $filter->apply($image));

        $this->assertCount(4, $capturedOptions);
        $this->assertEquals('custom-value', $capturedOptions['custom-option']);
        $this->assertEquals(ImageInterface::RESOLUTION_PIXELSPERINCH, $capturedOptions['resolution-units']);
        $this->assertEquals(72, $capturedOptions['resolution-x']);
        $this->assertEquals(100, $capturedOptions['resolution-y']);
    }
}
