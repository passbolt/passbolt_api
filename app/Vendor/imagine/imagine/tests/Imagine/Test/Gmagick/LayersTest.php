<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Gmagick;

use Imagine\Gmagick\Layers;
use Imagine\Gmagick\Image;
use Imagine\Gmagick\Imagine;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Test\Image\AbstractLayersTest;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;

class LayersTest extends AbstractLayersTest
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('Gmagick')) {
            $this->markTestSkipped('Gmagick is not installed');
        }
    }

    public function testCount()
    {
        $palette = new RGB();
        $resource = $this->getMockBuilder('\Gmagick')
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('getnumberimages')
            ->will($this->returnValue(42));

        $layers = new Layers(new Image($resource, $palette, new MetadataBag()), $palette, $resource);

        $this->assertCount(42, $layers);
    }

    public function testGetLayer()
    {
        $palette = new RGB();
        $resource = $this->getMockBuilder('\Gmagick')
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->any())
            ->method('getnumberimages')
            ->will($this->returnValue(2));

        $layer = $this->getMockBuilder('\Gmagick')
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->any())
            ->method('getimage')
            ->will($this->returnValue($layer));

        $layers = new Layers(new Image($resource, $palette, new MetadataBag()), $palette, $resource);

        foreach ($layers as $layer) {
            $this->assertInstanceOf('Imagine\Image\ImageInterface', $layer);
        }
    }

    public function testAnimateEmpty()
    {
        $this->markTestSkipped('Animate empty is skipped due to https://bugs.php.net/bug.php?id=62309');
    }

    public function getImage($path = null)
    {
        if ($path) {
            return new Image(new \Gmagick($path), new RGB(), new MetadataBag());
        } else {
            return new Image(new \Gmagick(), new RGB(), new MetadataBag());
        }
    }

    public function getImagine()
    {
        return new Imagine();
    }

    public function getLayers(ImageInterface $image, $resource)
    {
        return new Layers($image, $resource, new MetadataBag());
    }

    protected function assertLayersEquals($expected, $actual)
    {
        $this->assertEquals($expected->getGmagick(), $actual->getGmagick());
    }
}
