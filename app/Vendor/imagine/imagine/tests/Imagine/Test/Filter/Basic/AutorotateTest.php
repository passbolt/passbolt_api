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

USE Imagine\Filter\Basic\Autorotate;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Test\Filter\FilterTestCase;

class AutorotateTest extends FilterTestCase
{
    /**
     * @dataProvider provideMetadataAndRotations
     */
    public function testApply($expectedRotation, MetadataBag $metadata)
    {
        $image = $this->getImage();
        $image->expects($this->any())
            ->method('metadata')
            ->will($this->returnValue($metadata));

        if (null === $expectedRotation) {
            $image->expects($this->never())
                ->method('rotate');
        } else {
            $image->expects($this->once())
                ->method('rotate')
                ->with($expectedRotation);
        }

        $filter = new Autorotate($this->getColor());
        $filter->apply($image);
    }

    public function provideMetadataAndRotations()
    {
        return array(
            array(null, new MetadataBag(array())),
            array(null, new MetadataBag(array('ifd0.Orientation' => 0))),
            array(null, new MetadataBag(array('ifd0.Orientation' => 1))),
            array(null, new MetadataBag(array('ifd0.Orientation' => 2))),
            array(null, new MetadataBag(array('ifd0.Orientation' => null))),
            array(90, new MetadataBag(array('ifd0.Orientation' => 6))),
            array(180, new MetadataBag(array('ifd0.Orientation' => 3))),
            array(-90, new MetadataBag(array('ifd0.Orientation' => 8))),
        );
    }
}
