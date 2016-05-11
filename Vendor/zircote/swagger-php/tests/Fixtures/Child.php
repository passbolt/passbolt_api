<?php

namespace AnotherNamespace;

/**
 * @SWG\Definition()
 */
class Child extends \SwaggerFixtures\Parent
{

    /**
     * @var bool
     * @SWG\Property()
     */
    public $isBaby;
}
