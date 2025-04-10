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

namespace Passbolt\Tags\Test\TestCase\Model\Table\Users;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class SoftDeleteTest extends TagTestCase
{
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function testTagsUsersSoftDeleteAlsoDeletePersonalTagsSuccess()
    {
        [$userToDelete, $otherUser] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUsedByOtherUsers */
        $tagUsedByOtherUsers = TagFactory::make()->isPersonalFor($resource, $otherUser)->persist();

        TagFactory::make()->isPersonalFor($resource, $userToDelete)->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $sharedTag */
        $sharedTag = TagFactory::make()
            ->isPersonalFor($resource, $userToDelete)
            ->isPersonalFor($resource, $otherUser)
            ->isShared()
            ->persist();

        $this->Users->softDelete($userToDelete, ['checkRules' => false]);

        // alpha is still used by other users
        TagFactory::get($tagUsedByOtherUsers->id);
        // #bravo is used in other resources
        TagFactory::get($sharedTag->id);

        // Fox trot is only used by ada
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
    }

    public function testTagsUsersSoftDeleteAlsoDeleteSharedTagsSuccess()
    {
        $userToDelete = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        TagFactory::make()
            ->isPersonalFor($resource, $userToDelete)
            ->isShared()
            ->persist();

        $this->Users->softDelete($userToDelete, ['checkRules' => false]);

        // #echo was only used on one resource that ada used
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    public function testTagsUsersSoftDeleteAlsoDeleteSharedTagsSuccess2()
    {
        [$ada, $betty] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUsedByOtherUsers */
        TagFactory::make()
            ->isPersonalFor($resource, $ada)
            ->isPersonalFor($resource, $betty)
            ->isShared()
            ->persist();

        //Deleting Betty and Ada should delete alpha
        $this->Users->softDelete($ada, ['checkRules' => false]);
        $this->Users->softDelete($betty, ['checkRules' => false]);

        // alpha is not used by other users
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }
}
