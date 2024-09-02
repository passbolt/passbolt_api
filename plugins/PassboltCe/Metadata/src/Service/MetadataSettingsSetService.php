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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service;

use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;

class MetadataSettingsSetService
{
    use LocatorAwareTrait;

    /**
     * Validates and save the metadata settings
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data Data provided in the payload
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     * @throws \Cake\Http\Exception\UnauthorizedException When user role is not admin.
     * @throws \App\Error\Exception\CustomValidationException When there are validation errors.
     * @throws \Cake\Http\Exception\InternalErrorException|\Exception When unable to save the entity.
     * @return \Passbolt\Metadata\Model\Dto\MetadataSettingsDto dto
     */
    public function saveSettings(UserAccessControl $uac, array $data): MetadataSettingsDto
    {
        $uac->assertIsAdmin();

        $dto = (new MetadataSettingsAssertService())->assert($data);

        /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
        $orgSettingsTable = $this->fetchTable('OrganizationSettings');
        $orgSettingsTable->createOrUpdateSetting(
            MetadataSettingsGetService::ORG_SETTING_PROPERTY,
            $dto->toJson(),
            $uac
        );

        return $dto;
    }
}
