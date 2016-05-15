<?php

namespace PetstoreIO;

/**
 * @SWG\Definition(@SWG\Xml(name="User"))
 */
class User
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
    public $username;

    /**
     * @SWG\Property
     * @var string
     */
    public $firstName;

    /**
     * @SWG\Property()
     * @var string
     */
    public $lastName;

    /**
     * @var string
     * @SWG\Property()
     */
    public $email;

    /**
     * @var string
     * @SWG\Property()
     */
    public $password;

    /**
     * @var string
     * @SWG\Property()
     */
    public $phone;

    /**
     * User Status
     * @var int
     * @SWG\Property(format="int32")
     */
    public $userStatus;
}
