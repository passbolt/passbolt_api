<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Model\Table\Comments;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CleanupTest extends AppTestCase
{

    public $Comments;
    public $Groups;
    public $fixtures = [
        'app.Base/Users', 'app.Alt0/Permissions', 'app.Base/Resources', 'app.Base/Comments'
    ];
    public $options;

    use CleanupTrait;

    public function setUp()
    {
        parent::setUp();
        $this->Comments = TableRegistry::getTableLocator()->get('Comments');
        $this->options = ['accessibleFields' => [
            'user_id' => true,
            'foreign_model' => true,
            'foreign_key' => true,
            'content' => true,
            'created_by' => true,
            'modified_by' => true
        ]];
    }

    public function tearDown()
    {
        unset($this->Comments);
        parent::tearDown();
    }

    public function testCleanupCommentsSoftDeletedUsersSuccess()
    {
        $originalCount = $this->Comments->find()->count();
        $fav = $this->Comments->newEntity([
            'user_id' => UuidFactory::uuid('user.id.sofia'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.april'),
            'content' => 'test comment',
            'created_by' => UuidFactory::uuid('user.id.sofia'),
            'modified_by' => UuidFactory::uuid('user.id.sofia')
        ], $this->options);
        $this->Comments->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Comments', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupCommentsHardDeletedUsersSuccess()
    {
        $originalCount = $this->Comments->find()->count();
        $fav = $this->Comments->newEntity([
            'user_id' => UuidFactory::uuid('user.id.nope'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.april'),
            'content' => 'test comment',
            'created_by' => UuidFactory::uuid('user.id.nope'),
            'modified_by' => UuidFactory::uuid('user.id.nope')
        ], $this->options);
        $this->Comments->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Comments', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupCommentsSoftDeletedResourcesSuccess()
    {
        $originalCount = $this->Comments->find()->count();
        $fav = $this->Comments->newEntity([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'content' => 'test comment',
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada'),
        ], $this->options);
        $this->Comments->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Comments', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupCommentsHardDeletedResourcesSuccess()
    {
        $originalCount = $this->Comments->find()->count();
        $fav = $this->Comments->newEntity([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'foreign_model' => 'Resource',
            'foreign_key' => UuidFactory::uuid('resource.id.nope'),
            'content' => 'test comment',
            'created_by' => UuidFactory::uuid('user.id.ada'),
            'modified_by' => UuidFactory::uuid('user.id.ada')
        ], $this->options);
        $this->Comments->save($fav, ['checkRules' => false]);
        $this->runCleanupChecks('Comments', 'cleanupHardDeletedResources', $originalCount);
    }
}
