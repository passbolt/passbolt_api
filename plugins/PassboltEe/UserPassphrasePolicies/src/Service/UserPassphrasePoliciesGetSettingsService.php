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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Service;

use App\Error\Exception\FormValidationException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\UserPassphrasePolicies\Form\UserPassphrasePoliciesSettingsForm;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;

class UserPassphrasePoliciesGetSettingsService
{
    use LocatorAwareTrait;

    /**
     * Returns user passphrase policy settings. Settings will be retrieved from database if present
     * otherwise it will return the default settings.
     *
     * @return \Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto
     * @throws \App\Error\Exception\FormValidationException When settings could not validate
     */
    public function get(): UserPassphrasePoliciesSettingsDto
    {
        /** @var \Passbolt\UserPassphrasePolicies\Model\Table\UserPassphrasePoliciesSettingsTable $userPassphrasePoliciesSettingsTable */
        $userPassphrasePoliciesSettingsTable = $this->fetchTable('Passbolt/UserPassphrasePolicies.UserPassphrasePoliciesSettings'); // phpcs:ignore

        /** @var \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting|null $userPassphrasePoliciesSetting */
        $userPassphrasePoliciesSetting = $userPassphrasePoliciesSettingsTable->find()->first();

        // Fallback to default settings if no data in database
        if (is_null($userPassphrasePoliciesSetting)) {
            return UserPassphrasePoliciesSettingsDto::createFromDefault();
        }

        $form = new UserPassphrasePoliciesSettingsForm();
        if (!$form->execute($userPassphrasePoliciesSetting->value)) {
            throw new FormValidationException(
                __('Could not validate the user passphrase policies settings.'),
                $form
            );
        }

        return UserPassphrasePoliciesSettingsDto::createFromEntity($userPassphrasePoliciesSetting);
    }
}
