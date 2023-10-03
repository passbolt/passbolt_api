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
 * @since         2.13.0
 */

namespace App\Service\Secrets;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Secret;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SecretsCreateService
{
    /**
     * @var \App\Model\Table\SecretsTable
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
     * @param array $data The secret data
     * @param bool $checkRules Should run the table build rules. Default true.
     * @return \App\Model\Entity\Secret
     * @throws \Exception
     */
    public function create(array $data, bool $checkRules = true): Secret
    {
        $secret = $this->buildEntity($data);
        $this->handleValidationErrors($secret);

        $this->secretsTable->getConnection()->transactional(function () use ($secret, $checkRules) {
            $this->secretsTable->save($secret, ['checkRules' => $checkRules]);
            $this->handleValidationErrors($secret);
        });

        return $secret;
    }

    /**
     * Build the entity.
     *
     * @param array $data The secret data
     * @return \App\Model\Entity\Secret
     */
    private function buildEntity(array $data)
    {
        $data = [
            'resource_id' => Hash::get($data, 'resource_id'),
            'user_id' => Hash::get($data, 'user_id'),
            'data' => Hash::get($data, 'data'),
        ];
        $accessibleFields = [
            'resource_id' => true,
            'user_id' => true,
            'data' => true,
        ];

        return $this->secretsTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle validation errors.
     *
     * @param \App\Model\Entity\Secret $secret The secret
     * @return void
     */
    private function handleValidationErrors(Secret $secret)
    {
        $errors = $secret->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate secret data.'), $secret, $this->secretsTable);
        }
    }
}
