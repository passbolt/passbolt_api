<?php

/**
 * @SWG\Definition(
 *   required={"id","name"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class Pet
{

    /**
     * @SWG\Property(type="integer", format="int64")
     */
    public $id;
    /**
     * @SWG\Property(type="string")
     */
    public $name;

    /**
     * @SWG\Property(type="string")
     */
    public $tag;
}
