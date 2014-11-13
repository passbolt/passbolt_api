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

use Imagine\Image\Metadata\ExifMetadataReader;

class ExifMetadataReaderTest extends MetadataReaderTestCase
{
    protected function getReader()
    {
        return new ExifMetadataReader();
    }

    public function testExifDataAreReadWithReadFile()
    {
        $metadata = $this->getReader()->readFile(__DIR__ . '/../../../Fixtures/exifOrientation/90.jpg');
        $this->assertTrue(isset($metadata['ifd0.Orientation']));
        $this->assertEquals(6, $metadata['ifd0.Orientation']);
    }

    public function testExifDataAreReadWithReadData()
    {
        $metadata = $this->getReader()->readData(file_get_contents(__DIR__ . '/../../../Fixtures/exifOrientation/90.jpg'));
        $this->assertTrue(isset($metadata['ifd0.Orientation']));
        $this->assertEquals(6, $metadata['ifd0.Orientation']);
    }

    public function testExifDataAreReadWithReadStream()
    {
        $metadata = $this->getReader()->readStream(fopen(__DIR__ . '/../../../Fixtures/exifOrientation/90.jpg', 'r'));
        $this->assertTrue(isset($metadata['ifd0.Orientation']));
        $this->assertEquals(6, $metadata['ifd0.Orientation']);
    }
}
