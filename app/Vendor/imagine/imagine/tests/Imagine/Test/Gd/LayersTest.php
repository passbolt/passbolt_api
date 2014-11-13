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

use Imagine\Gd\Layers;
use Imagine\Gd\Image;
use Imagine\Gd\Imagine;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Test\Image\AbstractLayersTest;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;

class LayersTest extends AbstractLayersTest
{
    protected function setUp()
    {
        parent::setUp();

        if (!function_exists('gd_info')) {
            $this->markTestSkipped('Gd not installed');
        }
    }

    public function testCount()
    {
        $resource = imagecreate(20, 20);
        $palette = $this->getMock('Imagine\Image\Palette\PaletteInterface');
        $layers = new Layers(new Image($resource, $palette, new MetadataBag()), $palette, $resource);

        $this->assertCount(1, $layers);
    }

    public function testGetLayer()
    {
        $resource = imagecreate(20, 20);
        $palette = $this->getMock('Imagine\Image\Palette\PaletteInterface');
        $layers = new Layers(new Image($resource, $palette, new MetadataBag()), $palette, $resource);

        foreach ($layers as $layer) {
            $this->assertInstanceOf('Imagine\Image\ImageInterface', $layer);
        }
    }

    public function testLayerArrayAccess()
    {
        $image = $this->getImage(__DIR__ . "/../../Fixtures/pink.gif");
        $layers = $image->layers();

        $this->assertLayersEquals($image, $layers[0]);
        $this->assertTrue(isset($layers[0]));
    }

    public function testLayerAddGetSetRemove()
    {
        $image = $this->getImage(__DIR__ . "/../../Fixtures/pink.gif");
        $layers = $image->layers();

        $this->assertLayersEquals($image, $layers->get(0));
        $this->assertTrue($layers->has(0));
    }

    public function testLayerArrayAccessInvalidArgumentExceptions($offset = null)
    {
        $this->markTestSkipped('Gd does not fully support layers array access');
    }

    public function testLayerArrayAccessOutOfBoundsExceptions($offset = null)
    {
        $this->markTestSkipped('Gd does not fully support layers array access');
    }

    public function testAnimateEmpty()
    {
        $this->markTestSkipped('Gd does not support animated gifs');
    }

    public function testAnimateLoaded()
    {
        $this->markTestSkipped('Gd does not support animated gifs');
    }

    /**
     * @dataProvider provideAnimationParameters
     */
    public function testAnimateWithParameters($delay, $loops)
    {
        $this->markTestSkipped('Gd does not support animated gifs');
    }

    /**
     * @dataProvider provideAnimationParameters
     */
    public function testAnimateWithWrongParameters($delay, $loops)
    {
        $this->markTestSkipped('Gd does not support animated gifs');
    }

    public function getImage($path = null)
    {
        return new Image(imagecreatetruecolor(10, 10), new RGB(), new MetadataBag());
    }

    public function getLayers(ImageInterface $image, $resource)
    {
        return new Layers($image, new RGB(), $resource);
    }

    public function getImagine()
    {
        return new Imagine();
    }

    protected function assertLayersEquals($expected, $actual)
    {
        $this->assertEquals($expected->getGdResource(), $actual->getGdResource());
    }
}
