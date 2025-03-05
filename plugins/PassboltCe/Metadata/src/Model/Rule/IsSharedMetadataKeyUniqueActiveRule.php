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
 * @since         4.12.0
 */

namespace Passbolt\Metadata\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class IsSharedMetadataKeyUniqueActiveRule
{
    public const SKIP_RULE_OPTION = 'skipIsSharedMetadataKeyUniqueActiveRule';

    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if ($this->isRuleSkipped($options)) {
            return true;
        }

        return TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys')
            ->find()
            ->where([
                'expired IS NULL',
                'deleted IS NULL',
            ])->all()->count() < 2;
    }

    /**
     * By default, this rule is skipped. It will apply only when performing metadata key rotation or v4 to v5 upgrade
     *
     * @param array $options options
     * @return bool
     */
    private function isRuleSkipped(array $options): bool
    {
        return (bool)($options[self::SKIP_RULE_OPTION] ?? true);
    }
}
