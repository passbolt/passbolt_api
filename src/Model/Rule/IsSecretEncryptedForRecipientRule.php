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
 * @since         5.18.0
 */

namespace App\Model\Rule;

use App\Service\Secrets\SecretRecipientValidationService;
use Cake\Datasource\EntityInterface;
use Cake\Validation\Validation;

class IsSecretEncryptedForRecipientRule
{
    /**
     * Performs the check
     *
     * @param \Cake\Datasource\EntityInterface $entity The secret entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if (!SecretRecipientValidationService::isEnabled()) {
            return true;
        }

        if (!$entity->isNew() && !$entity->isDirty('data')) {
            return true;
        }

        $data = $entity->get('data');
        $userId = $entity->get('user_id');
        if (!is_string($data) || !is_string($userId) || !Validation::uuid($userId)) {
            return false;
        }

        return SecretRecipientValidationService::recipientKeyIdsMatchOnlyUserKey($data, $userId);
    }

    /**
     * Error message.
     *
     * @return string
     */
    public static function getMessage(): string
    {
        return __('The secret must be encrypted for the recipient user.');
    }
}
