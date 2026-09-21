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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Model\Rule;

use Cake\Datasource\EntityInterface;
use Passbolt\OfflineMode\Model\Rule\Checker\ResourceV5Checker;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;

class IsForeignItemV5Rule
{
    /**
     * @var array<string, \Passbolt\OfflineMode\Model\Rule\ForeignItemV5CheckerInterface>
     */
    private array $checkers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->checkers = [
            OfflineItemsTable::FOREIGN_MODEL_RESOURCE => new ResourceV5Checker(),
            // folder, not supported yet
        ];
    }

    /**
     * @param \Cake\Datasource\EntityInterface $entity Offline item being created.
     * @param array<string, mixed> $options Options array.
     * @return bool True when the target item is v5, or when the rule does not apply.
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $foreignModel = $entity->get('foreign_model');
        $foreignKey = $entity->get('foreign_key');
        if (!is_string($foreignModel) || !is_string($foreignKey) || $foreignKey === '') {
            return true;
        }

        $checker = $this->checkers[$foreignModel] ?? null;
        if ($checker === null) {
            return true;
        }

        return $checker->isV5($foreignKey);
    }
}
