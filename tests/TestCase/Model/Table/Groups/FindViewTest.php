<?php
namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class FindViewTest extends AppTestCase
{
    public $fixtures = ['app.Base/groups', 'app.Base/users', 'app.Base/groups_users'];

    public $Groups;

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::get('Groups', $config);
    }

    public function testSuccess()
    {
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $groups = $this->Groups->findView($groupId);

        // Expected fields.
        $group = $groups->first();
        $this->assertGroupAttributes($group);
        $this->assertEquals($groupId, $group->id);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('modifier', $group);
        $this->assertObjectNotHasAttribute('users', $group);
    }

    public function testErrorInvalidGroupIdParameter()
    {
        try {
            $this->Groups->findView('not-valid');
        } catch (\Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
