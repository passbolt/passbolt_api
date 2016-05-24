<?php

namespace PetstoreIO;

/**
 * @SWG\Definition(
 *   @SWG\Xml(name="Category")
 * )
 */
class Category
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
}
