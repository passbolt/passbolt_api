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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPoliciesUpdate\Service;

use App\Error\Exception\FormValidationException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsService;
use Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto;

class PasswordPoliciesUpdateGetSettingsService extends PasswordPoliciesGetSettingsService
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function get(): PasswordPoliciesSettingsDto
    {
        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Table\PasswordPoliciesSettingsTable $passwordPoliciesSettingsTable */
        $passwordPoliciesSettingsTable = $this->fetchTable('Passbolt/PasswordPoliciesUpdate.PasswordPoliciesSettings'); // phpcs:ignore

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting|null $passwordPoliciesSettings */
        $passwordPoliciesSettings = $passwordPoliciesSettingsTable->find()->first();

        // Fallback to default settings if no data in database
        if (is_null($passwordPoliciesSettings)) {
            return parent::get();
        }

        $form = new PasswordPoliciesSettingsForm();
        if (!$form->execute($passwordPoliciesSettings->value)) {
            throw new FormValidationException(__('Could not validate the password policies settings.'), $form);
        }

        return PasswordPoliciesUpdateSettingsDto::createFromEntity($passwordPoliciesSettings);
    }
}
