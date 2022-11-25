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
use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
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
     * @return \App\Model\Entity\OrganizationSetting
     */
    public function saveSettings(array $data): OrganizationSetting
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

        return $OrganizationSettings->createOrUpdateSetting(
            self::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME,
            $value,
            $this->uac
        );
    }

    /**
     * @param array $data data in the payload
     * @return \Passbolt\SelfRegistration\Form\SelfRegistrationBaseSettingsForm
     * @throws \Cake\Http\Exception\BadRequestException if the provider in the payload is not supported.
     */
    protected function getFormFromData(array $data): SelfRegistrationBaseSettingsForm
    {
        if (!isset($data['provider'])) {
            throw new BadRequestException(__('The provider is not defined'));
        }
        $provider = $data['provider'];
        switch ($provider) {
            // This is a placeholder for additional providers
            case 'email_domains':
            default:
                return new SelfRegistrationEmailDomainsSettingsForm();
        }
    }
}
