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

use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;
use Passbolt\Metadata\Service\MetadataSettingsSetService;
use Passbolt\Metadata\Test\Factory\MetadataSettingsFactory;

class MetadataSettingsSetServiceTest extends AppTestCase
{
    public function testMetadataSettingsSetService_Success_Create(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataSettingsFactory::getDefaultData();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataSettingsSetService();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
    }

    public function testMetadataSettingsSetService_Success_Edit(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataSettingsFactory::getDefaultData();
        $data[MetadataSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        MetadataSettingsFactory::make()->value(json_encode($data))->persist();
        $this->assertEquals(1, OrganizationSettingFactory::count());

        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataSettingsSetService();
        $data = MetadataSettingsFactory::getDefaultData();
        $dto = $sut->saveSettings($uac, $data);
        $this->assertEquals($data, $dto->toArray());
        $this->assertEquals(1, OrganizationSettingFactory::count());
    }

    public function testMetadataSettingsSetService_Error_NotAdmin(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $data = MetadataSettingsFactory::getDefaultData();
        $uac = new UserAccessControl(Role::USER, $user->id);
        $sut = new MetadataSettingsSetService();
        $this->expectException(ForbiddenException::class);
        $sut->saveSettings($uac, $data);
    }

    public function testMetadataSettingsSetService_Error_Invalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $data = MetadataSettingsFactory::getDefaultData();
        $data[MetadataSettingsDto::DEFAULT_RESOURCE_TYPES] = 'v8';
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $sut = new MetadataSettingsSetService();
        $this->expectException(FormValidationException::class);
        $sut->saveSettings($uac, $data);
    }
}
