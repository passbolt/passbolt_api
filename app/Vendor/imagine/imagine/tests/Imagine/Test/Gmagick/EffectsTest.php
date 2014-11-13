<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Gmagick;

use Imagine\Gmagick\Imagine;
use Imagine\Test\Effects\AbstractEffectsTest;

class EffectsTest extends AbstractEffectsTest
{
    protected function setUp()
    {
        parent::setUp();

        if (!class_exists('Gmagick')) {
            $this->markTestSkipped('Gmagick is not installed');
        }
    }

    public function testColorize()
    {
        $this->setExpectedException('RuntimeException');
        parent::testColorize();
    }

    protected function getImagine()
    {
        return new Imagine();
    }
}
