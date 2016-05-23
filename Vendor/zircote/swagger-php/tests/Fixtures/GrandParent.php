<?php
namespace SwaggerFixtures;

class GrandParent
{

    /**
     * @SWG\Property();
     * @var string
     */
    public $firstname;

    /**
     * @SWG\Property(property="lastname");
     * @var string
     */
    public $lastname;
}
