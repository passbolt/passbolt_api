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

namespace Passbolt\Tags\Test\TestCase\Model\Table\Groups;

use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Lib\TagTestCase;

class SoftDeleteTest extends TagTestCase
{
    public $Groups;
    public $GroupsUsers;
    public $Permissions;
    public $Resources;
    public $Users;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/favorites',
        'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/resources',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::get('Groups');
        $this->Tags = TableRegistry::get('Passbolt/Tags.Tags');
    }

    public function testGroupsSoftDeleteAlsoDeleteTagsSuccess()
    {
        $g = $this->Groups->get(UuidFactory::uuid('group.id.accounting'));
        $this->Groups->softDelete($g, ['checkRules' => false]);

        // Deleting Accounting or Cakephp should delete #charlie
        $this->expectException(RecordNotFoundException::class);
        $this->Tags->get('tag.id.#charlie');
    }
}
