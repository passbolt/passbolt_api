<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Effects;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\RGB;

abstract class AbstractEffectsTest extends \PHPUnit_Framework_TestCase
{

    public function testNegate()
    {
        $palette = new RGB();
        $imagine = $this->getImagine();

        $image = $imagine->create(new Box(20, 20), $palette->color('ff0'));
        $image->effects()
            ->negative();

        $this->assertEquals('#0000ff', (string) $image->getColorAt(new Point(10, 10)));

        $image->effects()
            ->negative();

        $this->assertEquals('#ffff00', (string) $image->getColorAt(new Point(10, 10)));
    }

    public function testGamma()
    {
        $palette = new RGB();
        $imagine = $this->getImagine();

        $r = 20;
        $g = 90;
        $b = 240;

        $image = $imagine->create(new Box(20, 20), $palette->color(array($r, $g, $b)));
        $image->effects()
            ->gamma(1.2);

        $pixel = $image->getColorAt(new Point(10, 10));

        $this->assertNotEquals($r, $pixel->getRed());
        $this->assertNotEquals($g, $pixel->getGreen());
        $this->assertNotEquals($b, $pixel->getBlue());
    }

    public function testGrayscale()
    {
        $palette = new RGB();
        $imagine = $this->getImagine();

        $r = 20;
        $g = 90;
        $b = 240;

        $image = $imagine->create(new Box(20, 20), $palette->color(array($r, $g, $b)));
        $image->effects()
            ->grayscale();

        $pixel = $image->getColorAt(new Point(10, 10));

        $this->assertEquals($this->getGrayValue(), (string) $pixel);

        $greyR = (int) $pixel->getRed();
        $greyG = (int) $pixel->getGreen();
        $greyB = (int) $pixel->getBlue();

        $this->assertEquals($greyR, $this->getComponentGrayValue());
        $this->assertEquals($greyR, $greyG);
        $this->assertEquals($greyR, $greyB);
        $this->assertEquals($greyG, $greyB);
    }

    protected function getGrayValue()
    {
        return '#565656';
    }

    protected function getComponentGrayValue()
    {
        return 86;
    }

    public function testColorize()
    {
        $palette = new RGB();
        $imagine = $this->getImagine();

        $blue = $palette->color('#0000FF');

        $image = $imagine->create(new Box(15, 15), $palette->color('000'));
        $image->effects()
            ->colorize($blue);

        $pixel = $image->getColorAt(new Point(10, 10));

        $this->assertEquals((string) $blue, (string) $pixel);

        $this->assertEquals($blue->getRed(), $pixel->getRed());
        $this->assertEquals($blue->getGreen(), $pixel->getGreen());
        $this->assertEquals($blue->getBlue(), $pixel->getBlue());
    }

    public function testBlur()
    {
        $palette = new RGB();
        $imagine = $this->getImagine();

        $image = $imagine->create(new Box(20, 20), $palette->color('#fff'));

        $image->draw()
            ->line(new Point(10, 0), new Point(10, 20), $palette->color('#000'), 1);

        $image->effects()
            ->blur();

        $pixel = $image->getColorAt(new Point(9, 10));

        $this->assertNotEquals(255, $pixel->getRed());
        $this->assertNotEquals(255, $pixel->getGreen());
        $this->assertNotEquals(255, $pixel->getBlue());
    }

    /**
     * @return ImagineInterface
     */
    abstract protected function getImagine();
}
