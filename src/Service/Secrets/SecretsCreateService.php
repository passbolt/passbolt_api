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
 * @since         2.14.0
 */

namespace App\Service\Secrets;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Secret;
use App\Model\Table\SecretsTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SecretsCreateService
{
    /**
     * @var SecretsTable
     */
    private $secretsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
    }

    /**
     * Create a secret.
     *
     * @param UserAccessControl $uac The operator
     * @param array $data The secret data
     * @return Secret
     * @throws \Exception
     */
    public function create(UserAccessControl $uac, array $data)
    {
        $secret = $this->buildEntity($uac, $data);
        $this->handleValidationErrors($secret);

        $this->secretsTable->getConnection()->transactional(function () use ($secret) {
            $this->secretsTable->save($secret);
            $this->handleValidationErrors($secret);
        });

        return $secret;
    }

    /**
     * Build the entity.
     *
     * @param UserAccessControl $uac The operator
     * @param array $data The secret data
     * @return Secret
     */
    private function buildEntity(UserAccessControl $uac, array $data)
    {
        $operatorId = $uac->userId();
        $data = [
            'resource_id' => Hash::get($data, 'resource_id'),
            'user_id' => Hash::get($data, 'user_id'),
            'data' => Hash::get($data, 'data'),
            'created_by' => $operatorId,
            'modified_by' => $operatorId,
        ];
        $accessibleFields = [
            'resource_id' => true,
            'user_id' => true,
            'data' => true,
            'create_by' => true,
            'modified_by' => true,
        ];

        return $this->secretsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle validation errors.
     *
     * @param Secret $secret The secret
     * @return void
     */
    private function handleValidationErrors(Secret $secret)
    {
        $errors = $secret->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate the secret data.'), $secret, $this->secretsTable);
        }
    }
}
