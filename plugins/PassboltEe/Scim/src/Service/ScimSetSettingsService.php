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
 * @since         4.1.0
 */
namespace Passbolt\Scim\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;

class ScimSetSettingsService extends ScimBaseSettingsService
{
    use EventDispatcherTrait;

    /**
     * Secret token prefix
     */
    public const SCIM_SECRET_TOKEN_PREFIX = 'pb_';

    public const SCIM_SETTINGS_UPDATE_EVENT_NAME = 'scim_settings_update_event_name';

    /**
     * @var \App\Utility\UserAccessControl
     */
    private UserAccessControl $uac;

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
     * @throws \Exception
     */
    public function saveSettings(array $data): array
    {
        $form = new ScimSettingsForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(
                __('Could not validate the SCIM settings.'),
                $form
            );
        }

        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $form->set('secret_token', $this->generateToken());
        $current = $OrganizationSettings->getByProperty(self::SCIM_SETTINGS_PROPERTY_NAME);
        if ($current) {
            $form->set('secret_token', Hash::get(json_decode($current->get('value'), true), 'secret_token'));
        }
        $value = json_encode($form->getData());
        $setting = $OrganizationSettings->createOrUpdateSetting(
            self::SCIM_SETTINGS_PROPERTY_NAME,
            $value,
            $this->uac
        );
        $renderedSettings = $this->getRenderedValue($setting, $form);

        $this->dispatchEvent(
            self::SCIM_SETTINGS_UPDATE_EVENT_NAME,
            $renderedSettings,
        );

        return $renderedSettings;
    }

    /**
     * Generate crypto-secure token for authentication
     *
     * @return string
     * @throws \Exception
     */
    public function generateToken(): string
    {
        $prefix = self::SCIM_SECRET_TOKEN_PREFIX;
        $token = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(random_bytes(3600)));

        return $prefix . substr($token, mt_rand(0, strlen($token) - 36), 36);
    }
}
