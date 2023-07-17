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
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Entity\SsoState;

class SsoStatesAssertService
{
    use LocatorAwareTrait;

    /**
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @param string $ssoSettingsId SSO Settings ID.
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When any assertions are failed.
     */
    public function assertAndConsume(SsoState $ssoState, string $ssoSettingsId, ExtendedUserAccessControl $uac): void
    {
        try {
            $this->assert($ssoState, $ssoSettingsId, $uac);
        } catch (BadRequestException $exception) {
            $this->consume($ssoState);

            throw $exception;
        }

        $this->consume($ssoState);
    }

    /**
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @param string $ssoSettingsId SSO Settings ID.
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When any assertions are failed.
     */
    public function assertAndConsumeWithoutUser(
        SsoState $ssoState,
        string $ssoSettingsId,
        ExtendedUserAccessControl $uac
    ): void {
        try {
            $this->assertWithoutUser($ssoState, $ssoSettingsId, $uac);
        } catch (BadRequestException $exception) {
            $this->consume($ssoState);

            throw $exception;
        }

        $this->consume($ssoState);
    }

    /**
     * Makes assertions against the SSO state entity, current user, and settings ID.
     * This is used to ensure data integrity between request user/client, settings.
     *
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @param string $ssoSettingsId SSO Settings ID.
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @return void
     */
    private function assert(SsoState $ssoState, string $ssoSettingsId, ExtendedUserAccessControl $uac): void
    {
        $errorMsg = __('The SSO state is invalid.') . ' ';

        if (!SsoState::isValidState($ssoState->state)) {
            throw new BadRequestException(trim($errorMsg));
        }

        if ($ssoState->isExpired()) {
            throw new BadRequestException($errorMsg . __('The SSO state is expired.'));
        }

        if ($ssoState->user_id !== $uac->getId() || !Validation::uuid($ssoState->user_id)) {
            throw new BadRequestException($errorMsg . __('User id mismatch.'));
        }

        if (Configure::read('passbolt.security.userIp')) {
            if ($ssoState->ip !== $uac->getUserIp()) {
                throw new BadRequestException($errorMsg . __('User IP mismatch.'));
            }
        }

        if (Configure::read('passbolt.security.userAgent')) {
            if ($ssoState->user_agent !== $uac->getUserAgent()) {
                throw new BadRequestException($errorMsg . __('User agent mismatch.'));
            }
        }

        if ($ssoState->sso_settings_id !== $ssoSettingsId || !Validation::uuid($ssoState->sso_settings_id)) {
            throw new BadRequestException($errorMsg . __('Settings mismatch.'));
        }
    }

    /**
     * Same assertions but without user ID.
     *
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @param string $ssoSettingsId SSO Settings ID.
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @return void
     */
    private function assertWithoutUser(SsoState $ssoState, string $ssoSettingsId, ExtendedUserAccessControl $uac): void
    {
        $errorMsg = __('The SSO state is invalid.') . ' ';

        if (!SsoState::isValidState($ssoState->state)) {
            throw new BadRequestException(trim($errorMsg));
        }

        if ($ssoState->isExpired()) {
            throw new BadRequestException($errorMsg . __('The SSO state is expired.'));
        }

        if (Configure::read('passbolt.security.userIp')) {
            if ($ssoState->ip !== $uac->getUserIp()) {
                throw new BadRequestException($errorMsg . __('User IP mismatch.'));
            }
        }

        if (Configure::read('passbolt.security.userAgent')) {
            if ($ssoState->user_agent !== $uac->getUserAgent()) {
                throw new BadRequestException($errorMsg . __('User agent mismatch.'));
            }
        }

        if ($ssoState->sso_settings_id !== $ssoSettingsId || !Validation::uuid($ssoState->sso_settings_id)) {
            throw new BadRequestException($errorMsg . __('Settings mismatch.'));
        }
    }

    /**
     * Marks given state as deleted.
     *
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When unable to save the SSO state entity.
     */
    private function consume(SsoState $ssoState): void
    {
        /** @var \Passbolt\Sso\Model\Table\SsoStatesTable $ssoStatesTable */
        $ssoStatesTable = $this->fetchTable('Passbolt/Sso.SsoStates');

        $ssoState->deleted = FrozenTime::now();

        if (!$ssoStatesTable->save($ssoState)) {
            throw new InternalErrorException(__('The SSO state could not be saved.'));
        }
    }
}
