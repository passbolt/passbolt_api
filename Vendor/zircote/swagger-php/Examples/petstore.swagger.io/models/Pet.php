<?php

namespace PetstoreIO;

/**
 * @SWG\Definition(required={"name", "photoUrls"}, @SWG\Xml(name="Pet"))
 */
class Pet
{

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(example="doggie")
     * @var string
     */
    public $name;

    /**
     * @var Category
     * @SWG\Property()
     */
    public $category;

    /**
     * @var string[]
     * @SWG\Property(@SWG\Xml(name="photoUrl",wrapped=true))
     */
    public $photoUrls;
    
    /**
     * @var Tag[]
     * @SWG\Property(@SWG\Xml(name="tag",wrapped=true))
     */
    public $tags;

    /**
     * pet status in the store
     * @var string
     * @SWG\Property(enum={"available", "pending", "sold"})
     */
    public $status;
}
