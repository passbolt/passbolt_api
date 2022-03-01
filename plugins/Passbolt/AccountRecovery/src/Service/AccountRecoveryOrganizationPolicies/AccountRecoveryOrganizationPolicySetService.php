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
 * @since         3.5.0
 */
namespace Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies;

use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable $AccountRecoveryOrganizationPolicies // phpcs:ignore
 */
class AccountRecoveryOrganizationPolicySetService extends AbstractAccountRecoveryOrganizationPolicySetService implements AccountRecoveryOrganizationPolicySetServiceInterface // phpcs:ignore
{
    /**
     * AbstractCompleteService constructor
     *
     * @param \Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetServiceInterface|null $getService bring your own getter
     */
    public function __construct(?AccountRecoveryOrganizationPolicyGetServiceInterface $getService = null)
    {
        parent::__construct($getService);
    }

    /**
     * Set - create new policy and delete the old one
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function set(UserAccessControl $uac, array $data): AccountRecoveryOrganizationPolicy
    {
        $this->setData($data);

        // assert policy is provided as it should in any case
        $newPolicy = $this->assertOrganizationPolicy($uac);

        // Check request composition to understand user goal
        $isPolicyChange = $this->isPolicyChange();
        $isNewKeyProvided = $this->isPublicKeyProvided();
        $isRevokedKeyProvided = $this->isRevokedKeyProvided();
        $isPrivateKeyPasswordsProvided = $this->isPrivateKeyPasswordsProvided();

        // if policy has not changed and (new key not provided or revoked key not provided)
        if (!$isPolicyChange && (!$isNewKeyProvided || !$isRevokedKeyProvided)) {
            throw new BadRequestException(__('Invalid request. No policy change.'));
        }

        // if disabled => new
        if ($this->isEnabling()) {
            // if public key is not provided
            if (!$isNewKeyProvided) {
                throw new BadRequestException(__('Invalid request. An organization recovery public key is required.'));
            }
            // if key revocation or passwords provided
            if ($isRevokedKeyProvided || $isPrivateKeyPasswordsProvided) {
                throw new BadRequestException(__('Invalid request. Revoked key or passwords are not required.'));
            }

            $publicKeyEntity = $this->assertNewPublicKey($uac);

            return $this->enablePolicy($newPolicy, $publicKeyEntity);
        }

        // if enabled => disabled
        if ($this->isDisabling()) {
            // if new key or passwords provided
            if ($isNewKeyProvided || $isPrivateKeyPasswordsProvided) {
                throw new BadRequestException(__('Invalid request. New key or passwords are not required.'));
            }
            $patchedKey = $this->assertAndPatchRevokedKeyEntity($uac);
            $newPolicy = $this->getNewDisabledEntity($uac);

            // save new disabled policy, disable previous key and delete backups if any
            return $this->disablePolicy($newPolicy, $patchedKey);
        }

//        // else it's policy change like mandatory => opt-in
//        // and/or a possible key rotation
//        if (($isNewKeyProvided && !$isRevokedKeyProvided) || (!$isNewKeyProvided && $isRevokedKeyProvided)) {
//            throw new BadRequestException(__('Invalid request. Keys are required for this change.'));
//        }
//
//        // if key provided or revocation provided
//        // assert old and new key
//        $rotateKey = false;
//        if ($isNewKeyProvided && $isRevokedKeyProvided) {
//            $this->assertPublicKey();
//            $this->assertRevokedKey();
//            $rotateKey = true;
//        }
//
//        // If some existing backups are present
//        // assert new backups are provided
//        $rotatePasswords = false;
//        if ($this->backupsExists()) {
//            // assert passwords backups format and numbers
//            $this->assertPasswordsBackups();
//            $rotatePasswords = true;
//        }
//
//        // save new key and disable previous key and backups if any
//        return $this->updatePolicy($rotateKey, $rotatePasswords);

        // + on save policy event
            // trigger email
    }

    /**
     * Save the new policy and trigger email notification
     *
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy entity
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $key entity
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    protected function enablePolicy(AccountRecoveryOrganizationPolicy $policy, AccountRecoveryOrganizationPublicKey $key): AccountRecoveryOrganizationPolicy // phpcs:ignore
    {
        // Validation has already been done at this point
        $this->AccountRecoveryOrganizationPolicies->getConnection()->transactional(function () use (&$policy, $key) {
            // Delete previous existing organization policy if any
            $current = $this->getCurrentPolicyEntity();
            if (isset($current->id)) {
                $this->AccountRecoveryOrganizationPolicies->delete($current);
            }

            // Create a new one
            $policy->account_recovery_organization_public_key = $key;
            $this->AccountRecoveryOrganizationPolicies->save($policy, [
                'validate' => false,
                'checkRules' => false,
                'associated' => [
                    'AccountRecoveryOrganizationPublicKeys' => ['validate' => false, 'checkRules' => false],
                ],
            ]);
        });

        // Trigger event for email notifications and co.
        $event = new Event(self::AFTER_ENABLE_POLICY_EVENT, $this, $policy);
        $this->AccountRecoveryOrganizationPolicies->getEventManager()->dispatch($event);

        return $policy;
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $newPolicy entity
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $patchedKey entity
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity
     * @throws \Exception
     */
    public function disablePolicy(
        AccountRecoveryOrganizationPolicy $newPolicy,
        AccountRecoveryOrganizationPublicKey $patchedKey
    ): AccountRecoveryOrganizationPolicy {
        $oldPolicy = $this->getCurrentPolicyEntity();
        $this->AccountRecoveryOrganizationPolicies->getConnection()->transactional(
            function () use ($oldPolicy, $newPolicy, $patchedKey) {
                $this->AccountRecoveryOrganizationPolicies->delete($oldPolicy, ['atomic' => false]);
                $this->AccountRecoveryOrganizationPolicies->save($newPolicy, ['atomic' => false]);
                $this->AccountRecoveryOrganizationPublicKeys->save($patchedKey, ['atomic' => false]);
                // TODO Delete backups
            }
        );

        // Trigger event for email notifications and co.
        $event = new Event(self::AFTER_DISABLE_POLICY_EVENT, $this, [$oldPolicy, $newPolicy]);
        $this->AccountRecoveryOrganizationPolicies->getEventManager()->dispatch($event);

        return $this->getCurrentPolicyEntity(false);
    }
}
