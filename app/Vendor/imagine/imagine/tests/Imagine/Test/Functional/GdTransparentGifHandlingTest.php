<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Functional;

use Imagine\Image\Point;
use Imagine\Gd\Imagine;
use Imagine\Exception\RuntimeException;

class GdTransparentGifHandlingTest extends \PHPUnit_Framework_TestCase
{
    private function getImagine()
    {
        try {
            $imagine = new Imagine();
        } catch (RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        return $imagine;
    }

    public function testShouldResize()
    {
        $imagine = $this->getImagine();
        $new     = sys_get_temp_dir()."/sample.jpeg";

        $image = $imagine->open('tests/Imagine/Fixtures/xparent.gif');
        $size  = $image->getSize()->scale(0.5);

        $image
            ->resize($size)
        ;

        $imagine
            ->create($size)
            ->paste($image, new Point(0, 0))
            ->save($new)
        ;

        unlink($new);
    }
}
