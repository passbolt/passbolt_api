<?php

namespace AnotherNamespace;

/**
 * @SWG\Definition()
 */
class ChildWithDocBlocks extends \SwaggerFixtures\ParentWithoutDocBlocks
{

    /**
     * @var bool
     * @SWG\Property()
     */
    public $isBaby;
}
