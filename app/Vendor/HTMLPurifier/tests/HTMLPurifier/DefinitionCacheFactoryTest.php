<?php

class HTMLPurifier_DefinitionCacheFactoryTest extends HTMLPurifier_Harness
{

    protected $factory;
    protected $oldFactory;

    public function setUp()
    {
        parent::setup();
        $this->factory = new HTMLPurifier_DefinitionCacheFactory();
        $this->oldFactory = HTMLPurifier_DefinitionCacheFactory::instance();
        HTMLPurifier_DefinitionCacheFactory::instance($this->factory);
    }

    public function tearDown()
    {
        HTMLPurifier_DefinitionCacheFactory::instance($this->oldFactory);
    }

    public function test_create()
    {
        $cache = $this->factory->create('Test', $this->config);
        $this->assertEqual($cache, new HTMLPurifier_DefinitionCache_Serializer('Test'));
    }

    public function test_create_withDecorator()
    {
        $this->factory->addDecorator('Memory');
        $cache = $this->factory->create('Test', $this->config);
        $cache_real = new HTMLPurifier_DefinitionCache_Decorator_Memory();
        $cache_real = $cache_real->decorate(new HTMLPurifier_DefinitionCache_Serializer('Test'));
        $this->assertEqual($cache, $cache_real);
    }

    public function test_create_withDecoratorObject()
    {
        $this->factory->addDecorator(new HTMLPurifier_DefinitionCache_Decorator_Memory());
        $cache = $this->factory->create('Test', $this->config);
        $cache_real = new HTMLPurifier_DefinitionCache_Decorator_Memory();
        $cache_real = $cache_real->decorate(new HTMLPurifier_DefinitionCache_Serializer('Test'));
        $this->assertEqual($cache, $cache_real);
    }

    public function test_create_recycling()
    {
        $cache  = $this->factory->create('Test', $this->config);
        $cache2 = $this->factory->create('Test', $this->config);
        $this->assertReference($cache, $cache2);
    }

    public function test_create_invalid()
    {
        $this->config->set('Cache.DefinitionImpl', 'Invalid');
        $this->expectError('Unrecognized DefinitionCache Invalid, using Serializer instead');
        $cache = $this->factory->create('Test', $this->config);
        $this->assertIsA($cache, 'HTMLPurifier_DefinitionCache_Serializer');
    }

    public function test_null()
    {
        $this->config->set('Cache.DefinitionImpl', null);
        $cache = $this->factory->create('Test', $this->config);
        $this->assertEqual($cache, new HTMLPurifier_DefinitionCache_Null('Test'));
    }

    public function test_register()
    {
        generate_mock_once('HTMLPurifier_DefinitionCache');
        $this->config->set('Cache.DefinitionImpl', 'TestCache');
        $this->factory->register('TestCache', $class = 'HTMLPurifier_DefinitionCacheMock');
        $cache = $this->factory->create('Test', $this->config);
        $this->assertIsA($cache, $class);
    }

}

// vim: et sw=4 sts=4
