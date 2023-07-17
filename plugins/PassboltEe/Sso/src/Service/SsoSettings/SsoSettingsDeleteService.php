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
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsDeleteService
{
    /**
     * Event names
     */
    public const AFTER_DELETE_ACTIVE_SSO_SETTINGS_EVENT = 'sso.ssosettings.delete.active';

    /**
     * Delete a setting identified with its id
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @param string $id uuid setting id
     * @throws \Cake\Http\Exception\BadRequestException if $id is not a valid uuid
     * @throws \Cake\Http\Exception\NotFoundException if settings cannot be found
     * @throws \Cake\Http\Exception\InternalErrorException if the settings could not be deleted
     * @return void
     */
    public function delete(ExtendedUserAccessControl $uac, string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO setting id should be a uuid.'));
        }
        $uac->assertIsAdmin();

        $ssoSettingsTable = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');

        try {
            /** @var \Passbolt\Sso\Model\Entity\SsoSetting $ssoSetting */
            $ssoSetting = $ssoSettingsTable->find()->where(['id' => $id])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The SSO setting does not exist.'), 404, $exception);
        }

        try {
            $ssoSettingsTable->query()
                ->delete()
                ->where(['id' => $id])
                ->execute();
        } catch (\Exception $exception) {
            throw new InternalErrorException(__('Could not delete the SSO settings.'), 500, $exception);
        }

        // Notify other administrators if an active policy was changed
        if ($ssoSetting->status === SsoSetting::STATUS_ACTIVE) {
            $event = new Event(
                self::AFTER_DELETE_ACTIVE_SSO_SETTINGS_EVENT,
                $this,
                ['uac' => $uac, 'ssoSetting' => $ssoSetting]
            );
            $ssoSettingsTable->getEventManager()->dispatch($event);
        }
    }

    /**
     * Delete all draft settings
     *
     * @param string $id uuid
     * @throws \Cake\Http\Exception\InternalErrorException if the settings could not be deleted
     * @return void
     */
    public function deleteAllBut(string $id): void
    {
        try {
            $ssoSettingsTable = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
            $ssoSettingsTable->query()
                ->delete()
                ->where(['id <>' => $id])
                ->execute();
        } catch (\Exception $exception) {
            throw new InternalErrorException(__('Could not delete the draft SSO settings.'), 500, $exception);
        }
    }
}
