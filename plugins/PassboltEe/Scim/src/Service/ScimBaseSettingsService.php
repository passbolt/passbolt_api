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

use App\Model\Entity\OrganizationSetting;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;

abstract class ScimBaseSettingsService
{
    public const SCIM_SETTINGS_PROPERTY_NAME = 'scim';

    /**
     * Renders the value merging the validated settings
     * with the created/modified related fields and the id.
     *
     * The form is passed in order to ensure that the data returned is sanitized
     *
     * @param \App\Model\Entity\OrganizationSetting $setting Setting in the DB
     * @param \Passbolt\Scim\Form\Settings\ScimSettingsForm $form Form validating the value of the setting
     * @return array
     */
    protected function getRenderedValue(OrganizationSetting $setting, ScimSettingsForm $form): array
    {
        $data = json_decode($setting->value, true, 2);
        return array_merge(
            $form->getData(),
            [
                'id' => $setting->id,
                'base_api_endpoint' => Router::url('scim/v2/' . Hash::get($data, 'setting_id'), true),
                'setting_id' => Hash::get($data, 'setting_id'),
                'scim_user_id' => Hash::get($data, 'scim_user_id'),
                'created' => $setting->modified,
                'modified' => $setting->modified,
                'created_by' => $setting->created_by,
                'modified_by' => $setting->modified_by,
            ]
        );
    }
}
