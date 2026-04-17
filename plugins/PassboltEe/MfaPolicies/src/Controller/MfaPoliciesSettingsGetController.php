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

namespace Passbolt\MfaPolicies\Controller;

use App\Controller\AppController;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;
use Passbolt\MfaPolicies\Service\MfaPoliciesGetSettingsService;

class MfaPoliciesSettingsGetController extends AppController
{
    /**
     * Returns MFA policies settings.
     *
     * @return void
     */
    public function get()
    {
        $getSettingsService = new MfaPoliciesGetSettingsService();
        $settings = $getSettingsService->get();

        $settings = $this->filterProperties($settings);

        $this->success(__('The operation was successful.'), $settings);
    }

    /**
     * Removes particular properties if its `null`.
     *
     * This is required because when settings are not present in the DB we get `null` properties,
     * which should be not present in the object as front-end doesn't care about those.
     *
     * @param \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings $mfaPolicySettings MFA policy settings DTO object.
     * @return \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings
     */
    private function filterProperties(MfaPolicySettings $mfaPolicySettings): MfaPolicySettings
    {
        foreach (['id', 'created', 'created_by', 'modified', 'modified_by'] as $property) {
            if ($mfaPolicySettings->{$property} === null) {
                unset($mfaPolicySettings->{$property});
            }
        }

        return $mfaPolicySettings;
    }
}
