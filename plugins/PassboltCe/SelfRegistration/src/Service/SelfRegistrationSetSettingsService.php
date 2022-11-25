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
 * @since         3.9.0
 */
namespace Passbolt\SelfRegistration\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\SelfRegistration\Form\SelfRegistrationBaseSettingsForm;
use Passbolt\SelfRegistration\Form\SelfRegistrationEmailDomainsSettingsForm;

class SelfRegistrationSetSettingsService
{
    public const USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME = 'selfRegistration';

    /**
     * @var \App\Utility\UserAccessControl
     */
    private $uac;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     */
    public function __construct(UserAccessControl $uac)
    {
        $this->uac = $uac;
    }

    /**
     * @param array $data data in the payload
     * @return array
     */
    public function saveSettings(array $data): array
    {
        $form = $this->getFormFromData($data);

        if (!$form->execute($data)) {
            throw new FormValidationException(
                __('Could not validate the self registration settings.'),
                $form
            );
        }

        $value = json_encode($form->getData());

        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        $setting = $OrganizationSettings->createOrUpdateSetting(
            self::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME,
            $value,
            $this->uac
        );

        return array_merge(
            [
                'id' => $setting->id,
            ],
            $form->getData(),
            [
                'created' => $setting->modified,
                'modified' => $setting->modified,
                'created_by' => $setting->created_by,
                'modified_by' => $setting->modified_by,
            ]
        );
    }

    /**
     * @param array $data data in the payload
     * @return \Passbolt\SelfRegistration\Form\SelfRegistrationBaseSettingsForm
     */
    protected function getFormFromData(array $data): SelfRegistrationBaseSettingsForm
    {
        $provider = $data['provider'] ?? null;
        switch ($provider) {
            // This is a placeholder for additional providers
            case SelfRegistrationBaseSettingsForm::SELF_REGISTRATION_EMAIL_DOMAINS:
                return new SelfRegistrationEmailDomainsSettingsForm();
            default:
                return new SelfRegistrationBaseSettingsForm();
        }
    }
}
