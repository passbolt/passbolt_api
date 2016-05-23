<?php
namespace SwaggerFixures;

use Exception;
use Swagger\Logger as SwgLogger;
use \Swagger\Logger;
use Swagger\Annotations as SWG;

/**
 * @SWG\Info(title="Fixture for ClassPropertiesTest", version="test")
 * @SWG\Definition()
 */
class Customer
{
    
    /**
     * The firstname of the customer.
     * @var string
     * @SWG\Property()
     */
    public $firstname;
    
    /**
     * @var string The lastname of the customer.
     * @SWG\Property()
     */
    public $lastname;
    
    /**
     * @SWG\Property()
     * @var string[]
     */
    public $tags;
    
    /**
     * @SWG\Property()
     * @var Customer
     */
    public $submittedBy;
    
    /**
     * @SWG\Property()
     * @var Customer[]
     */
    public $friends;

    /**
     * for ContextTest
     */
    public function testResolvingFullyQualifiedNames()
    {
        $test = new SwgLogger();
        $test2 = new Logger();
        $test3 = new SWG\Contact();
        throw new Exception();
    }
}
