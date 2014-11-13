<?php
/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Imagine\Test\Image\Fill\Gradient;

use Imagine\Image\Fill\Gradient\Vertical;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Point;

class VerticalTest extends LinearTest
{
    /**
     * (non-PHPdoc)
     * @see Imagine\Image\Fill\Gradient\LinearTest::getEnd()
     */
    protected function getEnd()
    {
        return $this->getColor('fff');
    }

    /**
     * (non-PHPdoc)
     * @see Imagine\Image\Fill\Gradient\LinearTest::getStart()
     */
    protected function getStart()
    {
        return $this->getColor('000');
    }

    /**
     * (non-PHPdoc)
     * @see Imagine\Image\Fill\Gradient\LinearTest::getMask()
     */
    protected function getFill(ColorInterface $start, ColorInterface $end)
    {
        return new Vertical(100, $start, $end);
    }

    /**
     * (non-PHPdoc)
     * @see Imagine\Image\Fill\Gradient\LinearTest::getPointsAndShades()
     */
    public function getPointsAndColors()
    {
        return array(
            array($this->getColor('fff'), new Point(5, 100)),
            array($this->getColor('000'), new Point(15, 0)),
            array($this->getColor(array(128, 128, 128)), new Point(25, 50))
        );
    }
}
