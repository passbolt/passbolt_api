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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Service;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto;

class SecretRevisionsSettingsSetService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    /**
     * Validates and save the secret revisions settings.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto $dto DTO.
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \Cake\Http\Exception\UnauthorizedException When user role is not admin.
     * @throws \App\Error\Exception\CustomValidationException When there are validation errors.
     * @throws \Cake\Http\Exception\InternalErrorException|\Exception When unable to save the entity.
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public function saveSettings(UserAccessControl $uac, SecretRevisionsSettingsDto $dto): OrganizationSetting
    {
        $uac->assertIsAdmin();

        /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
        $orgSettingsTable = $this->fetchTable('OrganizationSettings');
        $organizationSetting = $orgSettingsTable->createOrUpdateSetting(
            SecretRevisionsSettingsGetService::ORG_SETTING_PROPERTY,
            $dto->toJson(),
            $uac
        );

        return $organizationSetting;
    }
}
