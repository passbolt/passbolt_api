<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Image\Histogram;

use Imagine\Image\Histogram\Range;

class RangeTest extends \PHPUnit_Framework_TestCase
{
    private $start = 0;
    private $end   = 63;

    /**
     * @dataProvider getExpectedResultsAndValues
     *
     * @param Boolean $contains
     * @param integer $value
     */
    public function testShouldDetermineIfContainsValue($contains, $value)
    {
        $range = new Range($this->start, $this->end);

        $this->assertEquals($contains, $range->contains($value));
    }

    public function getExpectedResultsAndValues()
    {
        return array(
            array(true, 12),
            array(true, 0),
            array(false, 128),
            array(false, 63),
        );
    }

    /**
     * @expectedException Imagine\Exception\OutOfBoundsException
     */
    public function testShouldThrowExceptionIfEndIsSmallerThanStart()
    {
        new Range($this->end, $this->start);
    }
}
