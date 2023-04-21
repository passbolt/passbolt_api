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
        return array_merge(
            [
                'id' => $setting->id,
            ],
            $form->getData(),
            [
                'base_api_endpoint' => Router::url('scim/v2/' . $setting->id, true),
                'created' => $setting->modified,
                'modified' => $setting->modified,
                'created_by' => $setting->created_by,
                'modified_by' => $setting->modified_by,
            ]
        );
    }
}
