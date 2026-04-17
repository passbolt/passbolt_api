<?php
declare(strict_types=1);

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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class SoftDeleteTest extends TagTestCase
{
    public $Groups;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
    }

    public function testTagsGroupsSoftDeleteAlsoDeleteTagsSuccess()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$group])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        TagFactory::make()->isPersonalFor($resource, $user)->persist();
        TagFactory::make()->isSharedFor($resource)->persist();
        $this->Groups->softDelete($group, ['checkRules' => false]);
        $this->assertSame(0, TagFactory::count());
    }
}
