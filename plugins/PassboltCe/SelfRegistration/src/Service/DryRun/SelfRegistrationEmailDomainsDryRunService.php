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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Service\DryRun;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\FormValidationException;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Passbolt\SelfRegistration\Form\DryRun\SelfRegistrationEmailDomainsDryRunForm;

class SelfRegistrationEmailDomainsDryRunService extends SelfRegistrationAbstractDryRunService
{
    /**
     * @param array $data data in the payload
     * @return bool
     * @throws \Cake\Http\Exception\ForbiddenException if no allowed domains are found in the settings.
     * @throws \Cake\Http\Exception\ForbiddenException if the email is already registered.
     * @throws \App\Error\Exception\FormValidationException if no valid email is provided in the payload.
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB is invalid.
     */
    public function canGuestSelfRegister(array $data): bool
    {
        $form = new SelfRegistrationEmailDomainsDryRunForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(
                __('The self registration data could not be validated.'),
                $form
            );
        }

        $allowedDomains = $this->getAllowedDomainsInSettings();

        $email = $form->getData('email');
        $this->checkEmailDomainIsAllowed($email, $allowedDomains);
        $this->checkEmailNotPreviouslyRegistered($email);

        return true;
    }

    /**
     * @return array
     * @throws \Cake\Http\Exception\ForbiddenException if no allowed domains are found in the settings.
     * @throws \Cake\Http\Exception\InternalErrorException if the settings in DB are not valid.
     */
    protected function getAllowedDomainsInSettings(): array
    {
        // Fetch settings in DB
        $settings = $this->getSelfRegistrationSettingsInDB();
        $allowedDomains = $settings['data']['allowed_domains'] ?? null;
        if (is_null($allowedDomains)) {
            throw new ForbiddenException(__('The self registration is disabled.'));
        }

        return $allowedDomains;
    }

    /**
     * Check that the email complies to the allowed domains
     *
     * @param string $email Email to check
     * @param array $allowedDomains Allowed domains
     * @return void
     * @throws \App\Error\Exception\ValidationException if the email does not comply
     */
    protected function checkEmailDomainIsAllowed(string $email, array $allowedDomains): void
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        if (!$UsersTable->isUsernameCaseSensitive()) {
            $email = mb_strtolower($email);
            $allowedDomains = array_map('mb_strtolower', $allowedDomains);
        }

        $domain = explode('@', $email)[1] ?? null;
        $isDomainAllowed = in_array($domain, $allowedDomains);
        if (!$isDomainAllowed) {
            throw new CustomValidationException(__('The self registration data could not be validated.'), [
                'email' => [
                    'checkEmailDomainIsAllowed' => __('The domain is not supported for self-registration.'),
                ],
            ]);
        }
    }
}
