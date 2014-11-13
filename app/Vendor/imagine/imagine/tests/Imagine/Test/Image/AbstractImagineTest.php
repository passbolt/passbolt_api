<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Image;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Test\ImagineTestCase;
use Imagine\Image\Palette\RGB;
use Imagine\Image\ImagineInterface;

abstract class AbstractImagineTest extends ImagineTestCase
{
    public function testShouldCreateEmptyImage()
    {
        $factory = $this->getImagine();
        $image   = $factory->create(new Box(50, 50));
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(50, $size->getWidth());
        $this->assertEquals(50, $size->getHeight());
    }

    public function testShouldOpenAnImage()
    {
        $source = 'tests/Imagine/Fixtures/google.png';
        $factory = $this->getImagine();
        $image   = $factory->open($source);
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(364, $size->getWidth());
        $this->assertEquals(126, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertEquals($source, $metadata['uri']);
        $this->assertEquals(realpath($source), $metadata['filepath']);
    }

    public function testShouldOpenAnSplFileResource()
    {
        $source = 'tests/Imagine/Fixtures/google.png';
        $resource = new \SplFileInfo($source);
        $factory = $this->getImagine();
        $image   = $factory->open($resource);
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(364, $size->getWidth());
        $this->assertEquals(126, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertEquals($source, $metadata['uri']);
        $this->assertEquals(realpath($source), $metadata['filepath']);
    }

    public function testShouldFailOnUnknownImage()
    {
        $invalidResource = __DIR__.'/path/that/does/not/exist';

        $this->setExpectedException('Imagine\Exception\InvalidArgumentException', sprintf('File %s does not exist.', $invalidResource));
        $this->getImagine()->open($invalidResource);
    }

    public function testShouldOpenAnHttpImage()
    {
        $factory = $this->getImagine();
        $image   = $factory->open(self::HTTP_IMAGE);
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(280, $size->getWidth());
        $this->assertEquals(140, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertEquals(self::HTTP_IMAGE, $metadata['uri']);
        $this->assertArrayNotHasKey('filepath', $metadata);
    }

    public function testShouldCreateImageFromString()
    {
        $factory = $this->getImagine();
        $image   = $factory->load(file_get_contents('tests/Imagine/Fixtures/google.png'));
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(364, $size->getWidth());
        $this->assertEquals(126, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertArrayNotHasKey('uri', $metadata);
        $this->assertArrayNotHasKey('filepath', $metadata);
    }

    public function testShouldCreateImageFromResource()
    {
        $source = 'tests/Imagine/Fixtures/google.png';
        $factory = $this->getImagine();
        $resource = fopen($source, 'r');
        $image   = $factory->read($resource);
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(364, $size->getWidth());
        $this->assertEquals(126, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertEquals($source, $metadata['uri']);
        $this->assertEquals(realpath($source), $metadata['filepath']);
    }

    public function testShouldCreateImageFromHttpResource()
    {
        $factory = $this->getImagine();
        $resource = fopen(self::HTTP_IMAGE, 'r');
        $image   = $factory->read($resource);
        $size    = $image->getSize();

        $this->assertInstanceOf('Imagine\Image\ImageInterface', $image);
        $this->assertEquals(280, $size->getWidth());
        $this->assertEquals(140, $size->getHeight());

        $metadata = $image->metadata();

        $this->assertEquals(self::HTTP_IMAGE, $metadata['uri']);
        $this->assertArrayNotHasKey('filepath', $metadata);
    }

    public function testShouldDetermineFontSize()
    {
        if (!$this->isFontTestSupported()) {
            $this->markTestSkipped('This install does not support font tests');
        }

        $palette = new RGB();
        $path    = 'tests/Imagine/Fixtures/font/Arial.ttf';
        $black   = $palette->color('000');
        $factory = $this->getImagine();

        $this->assertEquals($this->getEstimatedFontBox(), $factory->font($path, 36, $black)->box('string'));
    }

    abstract protected function getEstimatedFontBox();

    /**
     * @return ImagineInterface
     */
    abstract protected function getImagine();

    abstract protected function isFontTestSupported();
}
