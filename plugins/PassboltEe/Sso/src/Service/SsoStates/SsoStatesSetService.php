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
 * @since         3.11.0
 */
namespace Passbolt\Sso\Service\SsoStates;

use App\Utility\ExtendedUserAccessControl;
use Cake\Core\Exception\CakeException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Sso\Model\Entity\SsoState;

class SsoStatesSetService
{
    use LocatorAwareTrait;

    /**
     * @param string $nonce Nonce to store
     * @param string $state State to store
     * @param string $type Type of SSO state.
     * @param string $ssoSettingsId SSO settings ID.
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @return \Passbolt\Sso\Model\Entity\SsoState
     * @throws \Cake\Http\Exception\InternalErrorException When unable to create the sso state.
     */
    public function create(
        string $nonce,
        string $state,
        string $type,
        string $ssoSettingsId,
        ExtendedUserAccessControl $uac
    ): SsoState {
        /** @var \Passbolt\Sso\Model\Table\SsoStatesTable $ssoStatesTable */
        $ssoStatesTable = $this->fetchTable('Passbolt/Sso.SsoStates');

        if (!SsoState::isValidState($nonce)) {
            throw new BadRequestException(__('Could not save the SSO state, invalid nonce.'));
        }

        try {
            $ssoState = $ssoStatesTable->newEntity(
                [
                    'nonce' => $nonce,
                    'state' => $state,
                    'type' => $type,
                    'sso_settings_id' => $ssoSettingsId,
                    'user_id' => $uac->getId() ?? null,
                    'ip' => $uac->getUserIp(),
                    'user_agent' => $uac->getUserAgent(),
                    'deleted' => FrozenTime::now()->modify('+' . SsoState::getExpiryDuration()),
                ],
                [
                    'accessibleFields' => [
                        'nonce' => true,
                        'state' => true,
                        'type' => true,
                        'sso_settings_id' => true,
                        'user_id' => true,
                        'ip' => true,
                        'user_agent' => true,
                        'deleted' => true,
                    ],
                ],
            );

            $ssoState = $ssoStatesTable->saveOrFail($ssoState);
        } catch (CakeException $e) {
            throw new InternalErrorException(
                __('Could not save the SSO state, please try again later.'),
                500,
                $e
            );
        }

        return $ssoState;
    }
}
