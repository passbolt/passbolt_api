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
 * @since         2.13.0
 */

namespace App\Service\Secrets;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Secret;
use App\Model\Table\SecretsTable;
use App\Model\Table\UsersTable;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SecretsUpdateSecretsService
{
    /**
     * @var PermissionsGetUsersIdsHavingAccessToService
     */
    private $permissionsGetUsersIdsHavingAccessToService;

    /**
     * @var SecretsUpdateSecretsService
     */
    private $secretCreateService;

    /**
     * @var SecretsTable
     */
    private $secretsTable;

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * Instantiate the constructor
     */
    public function __construct()
    {
        $this->permissionsGetUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->secretCreateService = new SecretsCreateService();
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Update a resource' secrets.
     *
     * @param UserAccessControl $uac The operator.
     * @param string $resourceId The resource to update the secrets for.
     * @param array $data The list of secrets to add
     * [
     *   [
     *     user_id <string> The user the secret is encrypted for
     *     data <string> The encrypted secret
     *   ],
     *   ...
     * ]
     * @return void
     * @throws \Exception If something unexpected occurred
     */
    public function updateSecrets(UserAccessControl $uac, string $resourceId, array $data = [])
    {
        foreach ($data as $rowIndex => $row) {
            $userId = Hash::get($row, 'user_id', null);
            $secret = $this->secretsTable->findByResourceIdAndUserId($resourceId, $userId)->first();
            if ($secret) {
                $this->updateSecret($secret, $rowIndex, $row);
            } else {
                $this->addSecret($uac, $rowIndex, $resourceId, $row);
            }
        }

        $this->deleteOrphanSecrets($resourceId);
        $this->assertAllSecretsAreProvided($resourceId);
    }

    /**
     * Update a secret for a resource and a user
     *
     * @param Secret $secret The secret to update
     * @param int $rowIndexRef The row index in the request data
     * @param array $data The secrets data
     * @return Secret
     * @throws \Exception
     */
    private function updateSecret(Secret $secret, int $rowIndexRef, array $data)
    {
        $patchEntityOptions = [
            'accessibleFields' => [
                'data' => true,
            ],
        ];
        $secret = $this->secretsTable->patchEntity($secret, $data, $patchEntityOptions);
        if ($secret->hasErrors()) {
            $errors = [$rowIndexRef => $secret->getErrors()];
            $this->handleValidationErrors($errors);
        }

        $this->secretsTable->save($secret);
        if ($secret->hasErrors()) {
            $errors = [$rowIndexRef => $secret->getErrors()];
            $this->handleValidationErrors($errors);
        }

        return $secret;
    }

    /**
     * Handle secrets validation errors.
     *
     * @param array $errors The list of errors
     * @return void
     * @throws ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(array $errors = [])
    {
        if (!empty($errors)) {
            throw new CustomValidationException(__('Could not validate secrets data.'), $errors, $this->secretsTable);
        }
    }

    /**
     * Add a secret for a resource and a user.
     *
     * @param UserAccessControl $uac The operator
     * @param int $rowIndexRef The row index in the request data
     * @param string $resourceId The target resource
     * @param array $data The secrets data
     * @return Secret
     * @throws \Exception
     */
    private function addSecret(UserAccessControl $uac, int $rowIndexRef, string $resourceId, array $data)
    {
        $secretData = [
            'resource_id' => $resourceId,
            'user_id' => Hash::get($data, 'user_id', ''),
            'data' => Hash::get($data, 'data', ''),
        ];

        try {
            return $this->secretCreateService->create($secretData);
        } catch (ValidationException $e) {
            $errors = [$rowIndexRef => $e->getErrors()];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Delete the secrets for which the users have no access.
     *
     * @param string $resourceId The target resource
     * @return void
     */
    private function deleteOrphanSecrets(string $resourceId)
    {
        $usersIds = $this->permissionsGetUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($resourceId);

        $this->secretsTable->deleteAll([
            'resource_id' => $resourceId,
            'user_id NOT IN' => $usersIds,
        ]);
    }

    /**
     * Ensure all secrets are provided.
     *
     * @param string $resourceId The target resource.
     * @return void
     */
    private function assertAllSecretsAreProvided(string $resourceId)
    {
        $usersIdsHavingAccess = $this->permissionsGetUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($resourceId);
        sort($usersIdsHavingAccess);
        $usersIdsHavingASecret = $this->secretsTable->findByResourceId($resourceId)->extract('user_id')->toArray();
        sort($usersIdsHavingASecret);

        if ($usersIdsHavingAccess !== $usersIdsHavingASecret) {
            $errors = ['secrets_provided' => 'The secrets of all the users having access to the resource are required.'];
            $this->handleValidationErrors($errors);
        }
    }
}
