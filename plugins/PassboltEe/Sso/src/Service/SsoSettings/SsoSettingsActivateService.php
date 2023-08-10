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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Service\SsoSettings;

use App\Utility\ExtendedUserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Dto\AbstractSsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenGetService;

class SsoSettingsActivateService
{
    /**
     * Event names
     */
    public const AFTER_ACTIVATE_SSO_SETTINGS_EVENT = 'sso.ssosettings.activate';

    /**
     * @var \Passbolt\Sso\Model\Table\SsoSettingsTable $SsoSettings
     */
    protected $SsoSettings;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->SsoSettings = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
    }

    /**
     * Return a setting identified with its id
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac user access controll
     * @param string $id uuid
     * @param array $data expects status and token
     * @throws \Cake\Http\Exception\BadRequestException if $id is not a valid uuid
     * @throws \Cake\Http\Exception\NotFoundException if settings cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if the settings could not be deleted
     * @return \Passbolt\Sso\Model\Dto\AbstractSsoSettingsDto
     */
    public function activate(ExtendedUserAccessControl $uac, string $id, array $data): AbstractSsoSettingsDto
    {
        // User must be an admin
        $uac->assertIsAdmin();

        // Trying to activate the settings
        $this->assertActiveStatus($data);

        // Status must in draft status
        $ssoSettingEntity = $this->assertAndGetSettings($id, SsoSetting::STATUS_DRAFT);

        // Token must be provided and matching the settings, user id, ip, user agent, etc.
        $authTokenService = new SsoAuthenticationTokenGetService();
        $type = SsoState::TYPE_SSO_SET_SETTINGS;

        // If token is not found remap error, not found in this context is reserved for settings
        try {
            $ssoAuthToken = $authTokenService->getOrFail($data['token'] ?? '', $type);
        } catch (RecordNotFoundException $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        // Consume or be consumed
        $authTokenService->assertAndConsume($ssoAuthToken, $uac, $ssoSettingEntity->id);

        // Activate
        try {
            $ssoSettingEntity->status = SsoSetting::STATUS_ACTIVE;
            $ssoSettingEntity->modified_by = $uac->getId();
            $this->SsoSettings->save($ssoSettingEntity);
            (new SsoSettingsDeleteService())->deleteAllBut($id);
        } catch (\Exception $exception) {
            throw new InternalErrorException(__('Could not update the SSO settings.'), 500, $exception);
        }

        // Notify settings have been changed
        $event = new Event(
            self::AFTER_ACTIVATE_SSO_SETTINGS_EVENT,
            $this,
            ['uac' => $uac, 'ssoSetting' => $ssoSettingEntity]
        );
        $this->SsoSettings->getEventManager()->dispatch($event);

        // Return new updated setting
        return (new SsoSettingsGetService())->getActiveOrFail(true);
    }

    /**
     * @param string $id uuid
     * @param string $status for example SsoSetting::STATUS_ACTIVE
     * @throws \Cake\Http\Exception\BadRequestException if the settings id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the settings id is not found
     * @return \Passbolt\Sso\Model\Entity\SsoSetting
     */
    protected function assertAndGetSettings(string $id, string $status): SsoSetting
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO setting id should be a uuid.'));
        }

        try {
            /** @phpstan-ignore-next-line */
            $this->SsoSettings = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
            /** @var \Passbolt\Sso\Model\Entity\SsoSetting $ssoSettings */
            $ssoSettings = $this->SsoSettings->find()->where(['id' => $id])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The SSO setting does not exist.'), 404, $exception);
        }

        if ($ssoSettings->status !== $status) {
            throw new BadRequestException(__('The settings status is invalid.'));
        }

        return $ssoSettings;
    }

    /**
     * @param array $data user provided data
     * @throws \Cake\Http\Exception\BadRequestException if status is invalid
     * @return void
     */
    protected function assertActiveStatus(array $data): void
    {
        if (!isset($data['status']) || $data['status'] !== SsoSetting::STATUS_ACTIVE) {
            throw new BadRequestException(__('Invalid status.'));
        }
    }
}
