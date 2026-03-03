<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table\Groups;

use App\Test\Factory\GroupFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Exception;

class FindViewTest extends AppTestCase
{
    public $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
    }

    public function testSuccess()
    {
        // Create group
        $group = GroupFactory::make()->persist();

        // Expected fields.
        $result = $this->Groups->findView($group->id)->first();
        $this->assertGroupAttributes($result);
        $this->assertEquals($group->id, $result->id);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('modifier', $result);
        $this->assertObjectNotHasAttribute('users', $result);
    }

    public function testErrorInvalidGroupIdParameter()
    {
        try {
            $this->Groups->findView('not-valid');
        } catch (Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
