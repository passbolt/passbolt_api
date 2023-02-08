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
namespace Passbolt\SelfRegistration\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\TableRegistry;

class SelfRegistrationSetSettingsService extends SelfRegistrationBaseSettingsService
{
    use EventDispatcherTrait;

    public const SELF_REGISTRATION_SETTINGS_UPDATE_EVENT_NAME = 'self_registration_settings_update_event_name';

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

        // @todo [FYI] see how the json content is handled by MfaPolicies and schedule a ticket to take care of it.
        $value = json_encode($form->getData());

        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        $setting = $OrganizationSettings->createOrUpdateSetting(
            self::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME,
            $value,
            $this->uac
        );
        $renderedSettings = $this->getRenderedValue($setting, $form);

        $this->dispatchEvent(
            self::SELF_REGISTRATION_SETTINGS_UPDATE_EVENT_NAME,
            $renderedSettings,
        );

        return $renderedSettings;
    }
}
