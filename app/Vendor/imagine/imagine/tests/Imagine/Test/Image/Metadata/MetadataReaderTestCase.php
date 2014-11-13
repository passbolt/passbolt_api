<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Image\Metadata;

use Imagine\Image\Metadata\MetadataReaderInterface;
use Imagine\Test\ImagineTestCase;

/**
 */
abstract class MetadataReaderTestCase extends ImagineTestCase
{
    /**
     * @return MetadataReaderInterface
     */
    abstract protected function getReader();

    public function testReadFromFile()
    {
        $source = __DIR__ . '/../../../Fixtures/pixel-CMYK.jpg';
        $metadata = $this->getReader()->readFile($source);
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
        $this->assertEquals(realpath($source), $metadata['filepath']);
        $this->assertEquals($source, $metadata['uri']);
    }

    public function testReadFromHttpFile()
    {
        $source = self::HTTP_IMAGE;
        $metadata = $this->getReader()->readFile($source);
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
        $this->assertFalse(isset($metadata['filepath']));
        $this->assertEquals($source, $metadata['uri']);
    }

    /**
     * @expectedException \Imagine\Exception\InvalidArgumentException
     * @expectedExceptionMessage File /path/to/no/file does not exist.
     */
    public function testReadFromInvalidFileThrowsAnException()
    {
        $this->getReader()->readFile('/path/to/no/file');
    }

    public function testReadFromData()
    {
        $source = __DIR__ . '/../../../Fixtures/pixel-CMYK.jpg';
        $metadata = $this->getReader()->readData(file_get_contents($source));
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
    }

    public function testReadFromInvalidDataDoesNotThrowException()
    {
        $metadata = $this->getReader()->readData('this is nonsense');
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
    }

    public function testReadFromStream()
    {
        $source = __DIR__ . '/../../../Fixtures/pixel-CMYK.jpg';
        $resource = fopen($source, 'r');
        $metadata = $this->getReader()->readStream($resource);
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
        $this->assertEquals(realpath($source), $metadata['filepath']);
        $this->assertEquals($source, $metadata['uri']);
    }

    /**
     * @expectedException \Imagine\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid resource provided.
     */
    public function testReadFromInvalidStreamThrowsAnException()
    {
        $metadata = $this->getReader()->readStream(false);
        $this->assertInstanceOf('Imagine\Image\Metadata\MetadataBag', $metadata);
    }
}
