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
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Resources',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
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
