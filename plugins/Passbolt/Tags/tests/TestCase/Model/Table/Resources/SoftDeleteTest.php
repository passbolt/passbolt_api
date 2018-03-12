<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Tags\Test\TestCase\Model\Table\Resources;

use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Lib\TagTestCase;

class SoftDeleteTest extends TagTestCase
{
    public $Resources;
    public $Tags;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/favorites',
        'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/resources',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Resources = TableRegistry::get('Resources');
        $this->Tags = TableRegistry::get('Passbolt/Tags.Tags');
    }

    public function tearDown()
    {
        unset($this->Resources);
        unset($this->Tags);
        parent::tearDown();
    }

    public function testResourceSoftDeleteAlsoDeletePersonalTagsSuccess()
    {
        $r = $this->Resources->get(UuidFactory::uuid('resource.id.apache'));
        $this->Resources->softDelete(UuidFactory::uuid('user.id.ada'), $r);

        // alpha is still used by other users
        $t = $this->Tags->get(UuidFactory::uuid('tag.id.alpha'));
        $this->assertNotEmpty($t->toArray());

        // #bravo is used in other resources
        $t = $this->Tags->get(UuidFactory::uuid('tag.id.#bravo'));
        $this->assertNotEmpty($t->toArray());

        // Fox-trot is only used on apache
        $this->expectException(RecordNotFoundException::class);
        $this->Tags->get(UuidFactory::uuid('tag.id.fox-trot'));
    }

    public function testResourceSoftDeleteAlsoDeleteSharedTagsSuccess()
    {
        $r = $this->Resources->get(UuidFactory::uuid('resource.id.apache'));
        $this->Resources->softDelete(UuidFactory::uuid('user.id.ada'), $r);

        // Fox-trot is only used on apache
        $this->expectException(RecordNotFoundException::class);
        $this->Tags->get(UuidFactory::uuid('tag.id.#echo'));
    }

    public function testResourceSoftDeleteAlsoDeleteSharedTagsViaGroupSuccess()
    {
        $r = $this->Resources->get(UuidFactory::uuid('resource.id.cakephp'));
        $this->Resources->softDelete(UuidFactory::uuid('user.id.ada'), $r);

        // #charlie is only used for cakephp owned by group accounting
        $this->expectException(RecordNotFoundException::class);
        $this->Tags->get(UuidFactory::uuid('tag.id.#cakephp'));
    }
}
