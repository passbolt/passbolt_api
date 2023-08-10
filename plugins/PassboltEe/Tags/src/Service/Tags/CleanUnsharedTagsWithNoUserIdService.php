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
 * @since         3.7.1
 */

namespace Passbolt\Tags\Service\Tags;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class CleanUnsharedTagsWithNoUserIdService
{
    /**
     * Removes all resources_tags entries for tags that are not shared
     * and for which the user_id is set to null.
     * This regression was introduced by 3.7
     *
     * @return int
     */
    public function cleanUp(): int
    {
        $ResourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        $entriesToDelete = $ResourcesTagsTable->find('list', ['displayField' => 'id'])
            ->select('ResourcesTags.id')
            ->disableHydration()
            ->innerJoinWith('Tags', function (Query $q) {
                return $q->where(['Tags.is_shared =' => 0]);
            })
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('ResourcesTags.user_id');
            })
            ->toArray();

        if (count($entriesToDelete) === 0) {
            return 0;
        }

        return $ResourcesTagsTable->deleteAll(['ResourcesTags.id IN' => $entriesToDelete]);
    }
}
