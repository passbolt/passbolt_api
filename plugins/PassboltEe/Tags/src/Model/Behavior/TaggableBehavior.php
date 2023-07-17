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
 * @since         3.7.0
 */

namespace Passbolt\Tags\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Decorate a model that is taggable.
 */
class TaggableBehavior extends Behavior
{
    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->table()->belongsToMany('Tags', [
            'through' => 'Passbolt/Tags.ResourcesTags',
            'className' => 'Passbolt/Tags.Tags',
        ]);
        $this->table()->hasMany('ResourcesTags', [
            'className' => 'Passbolt/Tags.ResourcesTags',
        ]);
    }
}
