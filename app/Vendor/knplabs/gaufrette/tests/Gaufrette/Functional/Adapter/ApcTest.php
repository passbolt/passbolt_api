<?php

namespace Gaufrette\Functional\Adapter;

use Gaufrette\Adapter\Apc;
use Gaufrette\Filesystem;

class ApcTest extends FunctionalTestCase
{
    public function setUp()
    {
        if (!extension_loaded('apc')) {
            return $this->markTestSkipped('The APC extension is not available.');
        } elseif (!ini_get('apc.enabled') || !ini_get('apc.enable_cli')) {
            return $this->markTestSkipped('The APC extension is available, but not enabled.');
        }

        apc_clear_cache('user');

        $this->filesystem = new Filesystem(new Apc('gaufrette-test.'));
    }

    public function tearDown()
    {
        parent::tearDown();
        if (extension_loaded('apc')) {
            apc_clear_cache('user');
        }
    }
}
