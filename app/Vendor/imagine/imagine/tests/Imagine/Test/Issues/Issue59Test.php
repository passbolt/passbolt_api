<?php

namespace Imagine\Test\Issues;

use Imagine\Gd\Imagine;
use Imagine\Exception\RuntimeException;

class Issue59Test extends \PHPUnit_Framework_TestCase
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

        $imagine
            ->open('tests/Imagine/Fixtures/sample.gif')
            ->save($new)
        ;

        unlink($new);
    }
}
