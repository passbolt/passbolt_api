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

namespace Passbolt\MfaPolicies\Service;

use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;

class MfaPoliciesGetSettingsService
{
    use LocatorAwareTrait;

    /**
     * Returns MFA policies settings.
     *
     * @return \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings
     * @throws \Cake\Http\Exception\InternalErrorException When value is not an array.
     */
    public function get(): MfaPolicySettings
    {
        /** @var \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable $mfaPoliciesSettingsTable */
        $mfaPoliciesSettingsTable = $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings');

        /** @var \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting|null $mfaPoliciesSettings */
        $mfaPoliciesSettings = $mfaPoliciesSettingsTable->find()->first();

        if ($mfaPoliciesSettings !== null) {
            if (!is_array($mfaPoliciesSettings->value)) {
                throw new InternalErrorException('The value should be an array');
            }

            return MfaPolicySettings::createFromEntity($mfaPoliciesSettings);
        }

        // Set default options
        $mfaPoliciesSettings = [
            'policy' => MfaPoliciesSetting::POLICY_OPT_IN,
            'remember_me_for_a_month' => true,
        ];

        return MfaPolicySettings::createFromArray($mfaPoliciesSettings);
    }
}
