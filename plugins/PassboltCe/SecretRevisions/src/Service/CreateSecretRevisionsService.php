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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Service;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Resource;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\SecretRevisions\Model\Entity\SecretRevision;
use Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable;

class CreateSecretRevisionsService
{
    use LocatorAwareTrait;

    private SecretRevisionsTable $SecretRevisions;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->SecretRevisions = $this->fetchTable('Passbolt/SecretRevisions.SecretRevisions');
    }

    /**
     * Creates a secret revision on resource creation and associates it to:
     * - the resource passed as parameter
     * - the secrets associated to this resource
     *
     * @param \App\Model\Entity\Resource $resource the resource being saved
     * @return \Passbolt\SecretRevisions\Model\Entity\SecretRevision
     */
    public function createFirstRevision(Resource $resource): SecretRevision
    {
        $userId = $resource->modified_by;

        $data = [
            'resource_id' => $resource->id,
            'resource_type_id' => $resource->resource_type_id,
            'created_by' => $userId,
            'modified_by' => $userId,
        ];
        $secretRevision = $this->SecretRevisions->newEntity($data, [
            'accessibleFields' => [
                'resource_id' => true,
                'resource_type_id' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);
        $secretRevision->secrets = $resource->secrets;
        // For performance, we explicitly set the fields of the secrets as non-accessible, so the data for example
        // of the secret is not persisted again.
        // The goal here is only to persist the secret_revision_id field
        foreach ($secretRevision->secrets as $secret) {
            $secret->setAccess('*', false);
        }
        $secretRevision->setDirty('secrets');
        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision $secretRevision */
        $secretRevision = $this->SecretRevisions->save($secretRevision);
        if (!$secretRevision) {
            throw new CustomValidationException(__('Could not save secret revision'), $secretRevision->getErrors());
        }

        return $secretRevision;
    }

    /**
     *  - Soft delete the previous secret revision and secrets
     *  - creates a secret revision on resource update and associates it to:
     * - the resource passed as parameter
     * - the secrets associated to this resource
     *
     * @param \App\Model\Entity\Resource $resource the resource being saved
     * @return \Passbolt\SecretRevisions\Model\Entity\SecretRevision
     */
    public function createNewRevision(Resource $resource): ?SecretRevision
    {
        if (empty($resource->secrets)) {
            return null;
        }
        $this->SecretRevisions->softDelete($resource->id);

        $userId = $resource->modified_by;

        $data = [
            'resource_id' => $resource->id,
            'resource_type_id' => $resource->resource_type_id,
            'created_by' => $userId,
            'modified_by' => $userId,
        ];
        $secretRevision = $this->SecretRevisions->newEntity($data, [
            'accessibleFields' => [
                'resource_id' => true,
                'resource_type_id' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        /** @var \Passbolt\SecretRevisions\Model\Entity\SecretRevision $secretRevision */
        $secretRevision = $this->SecretRevisions->save($secretRevision);
        if (!$secretRevision) {
            throw new CustomValidationException(__('Could not save secret revision'), $secretRevision->getErrors());
        }

        foreach ($resource->secrets as $secret) {
            $secret->set('secret_revision_id', $secretRevision->id);
        }

        return $secretRevision;
    }
}
