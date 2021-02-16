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
namespace PassboltTestData\Command\Large;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;
use Cake\Core\Configure;
use App\Model\Entity\Role;

class TagsDataCommand extends DataCommand
{
    public $entityName = 'Tags';

    /**
     * Get the tags data
     *
     * @return array
     */
    public function getData()
    {
        $tags = [];
        $tags = array_merge($tags, $this->getTagsScenarioForPersonalTags());
        $tags = array_merge($tags, $this->getTagsScenarioForSharedTags());

        return $tags;
    }

    public function getTagsScenarioForPersonalTags()
    {
        $tags = [];
        $this->loadModel('Users');

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.tags_personal');
        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            for ($i = 0; $i < $max; $i++) {
                $tags[] = [
                    'id' => UuidFactory::uuid("tag.id.personal-$i-{$user->id}"),
                    'slug' => "{$user->username} $i",
                    'is_shared' => 0
                ];
            }
        }

        return $tags;
    }

    public function getTagsScenarioForSharedTags()
    {
        $tags = [];

        $max = Configure::read('PassboltTestData.scenarios.large.install.count.tags_shared');
        for ($i = 0; $i < $max; $i++) {
            $tags[] = [
                'id' => UuidFactory::uuid("tag.id.shared-$i"),
                'slug' => "#Shared tag $i",
                'is_shared' => 1
            ];
        }

        return $tags;
    }
}
