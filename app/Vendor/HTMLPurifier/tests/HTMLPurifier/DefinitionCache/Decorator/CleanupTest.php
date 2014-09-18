<?php

generate_mock_once('HTMLPurifier_DefinitionCache');

class HTMLPurifier_DefinitionCache_Decorator_CleanupTest extends HTMLPurifier_DefinitionCache_DecoratorHarness
{

    public function setup()
    {
        $this->cache = new HTMLPurifier_DefinitionCache_Decorator_Cleanup();
        parent::setup();
    }

    public function setupMockForSuccess($op)
    {
        $this->mock->expectOnce($op, array($this->def, $this->config));
        $this->mock->setReturnValue($op, true, array($this->def, $this->config));
        $this->mock->expectNever('cleanup');
    }

    public function setupMockForFailure($op)
    {
        $this->mock->expectOnce($op, array($this->def, $this->config));
        $this->mock->setReturnValue($op, false, array($this->def, $this->config));
        $this->mock->expectOnce('cleanup', array($this->config));
    }

    public function test_get()
    {
        $this->mock->expectOnce('get', array($this->config));
        $this->mock->setReturnValue('get', true, array($this->config));
        $this->mock->expectNever('cleanup');
        $this->assertEqual($this->cache->get($this->config), $this->def);
    }

    public function test_get_failure()
    {
        $this->mock->expectOnce('get', array($this->config));
        $this->mock->setReturnValue('get', false, array($this->config));
        $this->mock->expectOnce('cleanup', array($this->config));
        $this->assertEqual($this->cache->get($this->config), false);
    }

    public function test_set()
    {
        $this->setupMockForSuccess('set');
        $this->assertEqual($this->cache->set($this->def, $this->config), true);
    }

    public function test_replace()
    {
        $this->setupMockForSuccess('replace');
        $this->assertEqual($this->cache->replace($this->def, $this->config), true);
    }

    public function test_add()
    {
        $this->setupMockForSuccess('add');
        $this->assertEqual($this->cache->add($this->def, $this->config), true);
    }

}

// vim: et sw=4 sts=4
