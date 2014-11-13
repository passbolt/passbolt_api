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

use Imagine\Image\Metadata\DefaultMetadataReader;

class DefaultMetadataReaderTest extends MetadataReaderTestCase
{
    protected function getReader()
    {
        return new DefaultMetadataReader();
    }
}
