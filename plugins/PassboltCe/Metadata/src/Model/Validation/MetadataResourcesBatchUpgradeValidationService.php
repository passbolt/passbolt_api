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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Model\Validation;

use App\Model\Table\PermissionsTable;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Form\Upgrade\MetadataBatchUpgradeForm;
use Passbolt\Metadata\Model\Entity\MetadataKey;

class MetadataResourcesBatchUpgradeValidationService extends MetadataBatchUpgradeValidationService
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return 'Resources';
    }

    /**
     * @inheritDoc
     */
    protected function queryEntitiesFromIds(array $entityIds): Query
    {
        $resources = parent::queryEntitiesFromIds($entityIds);

        return $resources
            ->selectAlso('resource_type_id')
            ->where(['Resources.deleted' => false]);
    }

    /**
     * @inheritDoc
     */
    public function getForm(): MetadataBatchUpgradeForm
    {
        return new MetadataBatchUpgradeForm();
    }

    /**
     * @inheritDoc
     */
    protected function setMetadataKeyIdIfNotDefinedAndEntityIsPersonal(array $entity): array
    {
        if (isset($entity['metadata_key_id'])) {
            return $entity;
        }
        $isPersonal = ($entity['metadata_key_type'] ?? null) === MetadataKey::TYPE_USER_KEY;
        if (!$isPersonal) {
            return $entity;
        }
        if (!isset($entity['id'])) {
            return $entity;
        }

        /** @var \App\Model\Table\PermissionsTable $Permissions */
        $Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $permission = $Permissions->find()
            ->select([
                'Permissions.aro_foreign_key',
                'Users.id',
                'Gpgkeys.id',
                'Gpgkeys.user_id',
            ])
            ->contain('Users.Gpgkeys')
            ->where([
                'Permissions.aco_foreign_key' => $entity['id'],
                'Permissions.aco' => PermissionsTable::RESOURCE_ACO,
                'Permissions.aro' => PermissionsTable::USER_ARO,
            ])
            ->disableHydration()
            ->first();

        if (!is_null($permission)) {
            $entity['metadata_key_id'] = $permission['user']['gpgkey']['id'] ?? null;
        }

        return $entity;
    }
}
