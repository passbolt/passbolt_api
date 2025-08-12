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

namespace Passbolt\Metadata\Test\TestCase\Service;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\MetadataTypesSettingsSetService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

/**
 * @covers \Passbolt\Metadata\Service\MetadataTypesSettingsSetService
 */
class MetadataTypesSettingsSetServiceTest extends AppTestCaseV5
{
    public function testMetadataTypesSettingsSetService_Success_Create(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataTypesSettingsSetService_Success_Edit(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $data[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        $data[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE] = true;
        $data[MetadataTypesSettingsDto::ALLOW_V4_V5_UPGRADE] = true;
        MetadataTypesSettingsFactory::make()->value(json_encode($data))->persist();
        $this->assertEquals(1, OrganizationSettingFactory::count());

        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
    }

    public function testMetadataTypesSettingsSetService_Error_NotAdmin(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $sut = new MetadataTypesSettingsSetService();
        $this->expectException(ForbiddenException::class);
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataTypesSettingsSetService_Error_Invalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $data[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES] = 'v8';
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();
        $this->expectException(FormValidationException::class);
        $sut->saveSettings($uac, $data);
    }

    /**
     * Error: v5 default selected but metadata key does not exist or is not available to all
     *
     * @return void
     */
    public function testMetadataTypesSettingsSetService_ErrorRules_No_MetadataKey_In_DB(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV5();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();
        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Unable to save the metadata settings. An active metadata key could not be found, create a key first.');
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataTypesSettingsSetService_ErrorRules_Deleted_MetadataKey_In_DB(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV5();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();

        MetadataKeyFactory::make()->deleted()->persist();

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Unable to save the metadata settings. An active metadata key could not be found, create a key first.');
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataTypesSettingsSetService_ErrorRules_Expired_MetadataKey_In_DB(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV5();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();

        MetadataKeyFactory::make()->expired()->persist();

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Unable to save the metadata settings. An active metadata key could not be found, create a key first.');
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataTypesSettingsSetService_ErrorRules_Valid_MetadataKey_In_DB(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataTypesSettingsFactory::getDefaultDataV5();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataTypesSettingsSetService();

        MetadataKeyFactory::make()->expired()->persist();
        MetadataKeyFactory::make()->deleted()->persist();
        MetadataKeyFactory::make()->persist();

        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }
}
