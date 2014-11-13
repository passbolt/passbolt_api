<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Image\Point;

use Imagine\Image\Point\Center;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class CenterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Imagine\Image\Point\Center::getX
     * @covers Imagine\Image\Point\Center::getY
     *
     * @dataProvider getSizesAndCoordinates
     *
     * @param Imagine\Image\BoxInterface   $box
     * @param Imagine\Image\PointInterface $expected
     */
    public function testShouldGetCenterCoordinates(BoxInterface $box, PointInterface $expected)
    {
        $point = new Center($box);

        $this->assertEquals($expected->getX(), $point->getX());
        $this->assertEquals($expected->getY(), $point->getY());
    }

    /**
     * Data provider for testShouldGetCenterCoordinates
     *
     * @return array
     */
    public function getSizesAndCoordinates()
    {
        return array(
            array(new Box(10, 15), new Point(5, 8)),
            array(new Box(40, 23), new Point(20, 12)),
            array(new Box(14, 8), new Point(7, 4)),
        );
    }

    /**
     * @covers Imagine\Image\Point::getX
     * @covers Imagine\Image\Point::getY
     * @covers Imagine\Image\Point::move
     *
     * @dataProvider getMoves
     *
     * @param Imagine\Image\BoxInterface $box
     * @param integer                    $move
     * @param integer                    $x1
     * @param integer                    $y1
     */
    public function testShouldMoveByGivenAmount(BoxInterface $box, $move, $x1, $y1)
    {
        $point = new Center($box);
        $shift = $point->move($move);

        $this->assertEquals($x1, $shift->getX());
        $this->assertEquals($y1, $shift->getY());
    }

    public function getMoves()
    {
        return array(
            array(new Box(10, 20), 5, 10, 15),
            array(new Box(5, 37), 2, 5, 21),
        );
    }

    /**
     * @covers Imagine\Image\Point\Center::__toString
     */
    public function testToString()
    {
        $this->assertEquals('(50, 50)', (string) new Center(new Box(100, 100)));
    }
}
