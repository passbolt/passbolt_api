<?php
declare(strict_types=1);

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
namespace Passbolt\TestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\TestData\Lib\DataCommand;

class TagsDataCommand extends DataCommand
{
    public string $entityName = 'Tags';

    /**
     * Get the tags data
     *
     * @return array
     */
    public function getData(): array
    {
        $tags = [];
        $tags = array_merge($tags, $this->getTagsScenarioForPersonalTags());
        $tags = array_merge($tags, $this->getTagsScenarioForSharedTags());

        return $tags;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTagsScenarioForPersonalTags(): array
    {
        $tags = [];
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.tags_personal');
        $users = $usersTable->findIndex(Role::USER);
        foreach ($users as $user) {
            for ($i = 0; $i < $max; $i++) {
                $tags[] = [
                    'id' => UuidFactory::uuid("tag.id.personal-$i-{$user->get('id')}"),
                    'slug' => "{$user->get('username')} $i",
                    'is_shared' => 0,
                ];
            }
        }

        return $tags;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTagsScenarioForSharedTags(): array
    {
        $tags = [];

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.tags_shared');
        for ($i = 0; $i < $max; $i++) {
            $tags[] = [
                'id' => UuidFactory::uuid("tag.id.shared-$i"),
                'slug' => "#Shared tag $i",
                'is_shared' => 1,
            ];
        }

        return $tags;
    }
}
