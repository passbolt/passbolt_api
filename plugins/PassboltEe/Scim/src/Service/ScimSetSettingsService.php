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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
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
     * @param \App\Utility\UserAccessControl $uac
     * @param array $data
     * @param string|null $id
     * @return array
     * @throws \Exception
     */
    public function saveSettings(UserAccessControl $uac, array $data, ?string $id = null): array
    {
        $form = new ScimSettingsForm();
        if ($id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The SCIM setting identifier should be a valid UUID.'));
            }
            $data['id'] = $id;
        }

        // Using this approach to avoid checking for setting_id duplicates on update
        $validate = $id ? 'update' : 'extended';
        if (!$form->execute($data, ['validate' => $validate])) {
            throw new FormValidationException(
                __('Could not validate the SCIM settings.'),
                $form
            );
        }

        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting|null $current */
        $current = $scimSettingsTable->find()->first();
        if (!$current && $id) {
            throw new NotFoundException(__('The SCIM plugin is disabled.'));
        }
        if (!$id && $current) {
            throw new BadRequestException(__('Please delete previous settings before creating again.'));
        }
        if ($current && $current->id !== $id) {
            throw new NotFoundException(__('The uuid in the url doesn\'t match any known setting record.'));
        }

        if ($current) {
            $currentValue = $this->decryptSettings($current);
            $form->set('setting_id', Hash::get($currentValue, 'setting_id'));
            if (!$form->getData('secret_token')) {
                $form->set('secret_token', Hash::get($currentValue, 'secret_token'));
            }
        }

        $value = $this->encryptSettings($form->getData());
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $setting */
        $setting = $scimSettingsTable->createOrUpdateSetting(
            $scimSettingsTable->getProperty(),
            $value,
            $uac
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
    public static function generateToken(): string
    {
        // Generate 256-bit entropy token
        $bin = random_bytes(32);
        // Base64 gives exact 43 characters (cutting "==" from last)
        $token = rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');

        // Including prefix total length becomes 46
        return self::SCIM_SECRET_TOKEN_PREFIX . $token;
    }
}
