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

use App\Model\Entity\AuthenticationToken;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;

class MfaPoliciesSetSettingsService
{
    use LocatorAwareTrait;
    use EventDispatcherTrait;

    /**
     * Event name. Fired after MFA policies settings has been saved.
     *
     * @var string
     */
    public const EVENT_SETTINGS_UPDATED = 'Service.MfaPoliciesSetSettings.updated';

    /**
     * Create MFA policies settings if not present already in DB or updates the settings value if already exists.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac Extended user access control.
     * @param \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings $mfaPolicySettingsDto DTO object.
     * @return \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings
     */
    public function createOrUpdate(
        ExtendedUserAccessControl $uac,
        MfaPolicySettings $mfaPolicySettingsDto
    ): MfaPolicySettings {
        if (!$uac->isAdmin()) {
            throw new ForbiddenException(
                __('Only administrators are allowed to create/update MFA policies settings.')
            );
        }

        $originalMfaPoliciesSettingDto = (new MfaPoliciesGetSettingsService())->get();

        /** @var \Passbolt\MfaPolicies\Model\Table\MfaPoliciesSettingsTable $mfaPoliciesSettingsTable */
        $mfaPoliciesSettingsTable = $this->fetchTable('Passbolt/MfaPolicies.MfaPoliciesSettings');

        /** @var \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting $mfaPoliciesSetting */
        $mfaPoliciesSetting = $mfaPoliciesSettingsTable->createOrUpdateSetting(
            $mfaPoliciesSettingsTable->getProperty(),
            [
                'policy' => $mfaPolicySettingsDto->policy,
                'remember_me_for_a_month' => $mfaPolicySettingsDto->remember_me_for_a_month,
            ],
            $uac
        );

        $mfaPolicySettingsDto = MfaPolicySettings::createFromEntity($mfaPoliciesSetting);

        /** Dispatch settings updated event. */
        $this->dispatchEvent(self::EVENT_SETTINGS_UPDATED, [
            'mfaPoliciesSetting' => $mfaPolicySettingsDto,
            'uac' => $uac,
        ]);

        $this->updateMfaAuthenticationTokens($originalMfaPoliciesSettingDto, $mfaPolicySettingsDto);

        return $mfaPolicySettingsDto;
    }

    /**
     * Updates MFA authentication tokens value if required.
     *
     * @param \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings $old Old MFA policy settings.
     * @param \Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings $new New MFA policy settings.
     * @return void
     */
    private function updateMfaAuthenticationTokens(MfaPolicySettings $old, MfaPolicySettings $new): void
    {
        if ($old->remember_me_for_a_month === false || $new->remember_me_for_a_month === true) {
            return;
        }

        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokensTable */
        $authenticationTokensTable = $this->fetchTable('AuthenticationTokens');
        /** @var \App\Model\Entity\AuthenticationToken[] $activeMfaTokens */
        $activeMfaTokens = $authenticationTokensTable
            ->find()
            ->where(['active' => true, 'type' => AuthenticationToken::TYPE_MFA])
            ->toArray();

        foreach ($activeMfaTokens as $activeMfaToken) {
            $data = $activeMfaToken->getJsonDecodedData();

            if (!isset($data['remember']) || $data['remember'] === false) {
                continue;
            }

            $data['remember'] = false;
            $activeMfaToken->data = json_encode($data);
            $authenticationTokensTable->save($activeMfaToken);
        }
    }
}
