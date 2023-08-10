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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Service\AccountRecoveryPrivateKeyPasswords;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class AccountRecoveryPrivateKeyPasswordsValidationService
 *
 * @package Passbolt\AccountRecovery\Service\AccountRecoveryPrivateKeyPasswords
 */
class AccountRecoveryPrivateKeyPasswordsValidationService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
     */
    protected $AccountRecoveryPrivateKeyPasswords;

    /**
     * AccountRecoveryPrivateKeyPasswordsValidationService constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeyPasswords = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $passwordsData user provided data
     * @param string $armoredKey key to check the message against
     * @param string $validationRules ruleset default to "default"
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword[] array of AccountRecoveryPrivateKeyPasswords
     */
    public function buildPasswordEntitiesFromDataOrFail(
        UserAccessControl $uac,
        array $passwordsData,
        string $armoredKey,
        string $validationRules = 'default'
    ): array {
        // Validate entities or fail
        $passwordEntities = $this->AccountRecoveryPrivateKeyPasswords
            ->buildAndValidateEntities($uac, $passwordsData, $validationRules);

        // Check that each message is addressed to the right recipient
        // e.g. that the sub key id is found in the sub packets
        $errors = [];
        $keyInfo = PublicKeyValidationService::getPublicKeyInfo($armoredKey);
        $rules = MessageValidationService::getAsymmetricMessageRules();
        foreach ($passwordEntities as $i => $entity) {
            // Parse message for additional information
            try {
                $msgInfo = MessageValidationService::parseAndValidateMessage($entity->data, $rules);
            } catch (CustomValidationException $exception) {
                $errors[$i] = $exception->getErrors();
                continue;
            }

            // Check provided fingerprint matches the org key
            $msg = __('The message is not encrypted for the right recipient.');
            if ($keyInfo['fingerprint'] !== $entity->recipient_fingerprint) {
                $errors[$i]['recipient_fingerprint']['wrongRecipient'] = $msg;
                continue;
            }

            // Check subkey id in message packet
            if (!MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo)) {
                $errors[$i]['data']['wrongRecipient'] = $msg;
                continue;
            }

            // Check business rules
            if (!$this->AccountRecoveryPrivateKeyPasswords->checkRules($entity)) {
                $errors[$i] = $entity->getErrors();
            }
        }

        // Throw an error on business rules or message composition
        if (count($errors)) {
            throw new CustomValidationException(__('Could not validate password data.'), [
                'account_recovery_private_key_passwords' => $errors,
            ]);
        }

        return $passwordEntities;
    }
}
