<?php

namespace Petstore;

/**
 * @SWG\Definition(definition="pet", required={"id", "name"})
 */
class SimplePet
{

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property()
     * @var string
     */
    public $name;

    /**
     * @var string
     * @SWG\Property()
     */
    public $tag;
}
