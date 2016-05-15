<?php

namespace petstore;

/**
 * @SWG\Definition(required={"id", "name"})
 */
class Pet
{

    /**
     * @SWG\Property(type="integer", format="int64")
     */
    public $id;

    /**
     * @SWG\Property()
     * @var string
     */
    public $name;

    /**
     * @SWG\Property()
     * @var string
     */
    public $tag;
}
