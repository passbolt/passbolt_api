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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class PasswordExpiryResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        PasswordExpirySettingFactory::make()->persist();
    }

    public function testPasswordExpiryResourcesUpdateController_Update_Expiry_Date_In_Future(): void
    {
        RoleFactory::make()->guest()->persist();
        ResourceTypeFactory::make()->default()->persist();

        $operator = UserFactory::make()->user()->persist();

        /** @var \App\Model\Entity\Resource $resourceToUpdate */
        $resourceToUpdate = ResourceFactory::make()->expired()->withPermissionsFor([$operator])->persist();
        $this->assertTrue($resourceToUpdate->isExpired());
        $this->logInAs($operator);

        $data = [
            'expired' => FrozenDate::tomorrow()->toAtomString(),
        ];
        $this->putJson("/resources/$resourceToUpdate->id.json", $data);
        $this->assertSuccess();

        $resourceUpdated = ResourceFactory::get($resourceToUpdate->id);
        $this->assertFalse($resourceUpdated->isExpired());
        $this->assertEmailQueueCount(1);
    }

    public function isResourceAlreadyExpired(): array
    {
        return [
            [false],
            [true],
        ];
    }

    /**
     * @dataProvider isResourceAlreadyExpired
     */
    public function testPasswordExpiryResourcesUpdateController_Update_Expiry_Date_In_Past(bool $isResourceAlreadyExpired): void
    {
        RoleFactory::make()->guest()->persist();
        ResourceTypeFactory::make()->default()->persist();

        if ($isResourceAlreadyExpired) {
            $expiryDate = FrozenTime::yesterday();
        } else {
            $expiryDate = FrozenTime::tomorrow();
        }
        [$operator, $ownerWithAccess, $editorWithAccess] = UserFactory::make(4)->user()->persist();
        /** @var \App\Model\Entity\Resource $resourceToUpdate */
        $resourceToUpdate = ResourceFactory::make(['name' => 'Foo'])
            ->expired($expiryDate)
            ->withPermissionsFor([$operator, $ownerWithAccess])
            ->withPermissionsFor([$editorWithAccess], Permission::UPDATE)
            ->withSecretsFor([$operator, $ownerWithAccess, $editorWithAccess])
            ->persist();
        $this->logInAs($operator);

        $data = [
            'name' => 'Foo updated',
            'expired' => FrozenDate::yesterday()->toAtomString(),
        ];
        $this->putJson("/resources/$resourceToUpdate->id.json", $data);
        $this->assertSuccess();

        $resourceUpdated = ResourceFactory::get($resourceToUpdate->id);
        $this->assertTrue($resourceUpdated->isExpired());
        // If the resource is already expired, do not notify the owner who is not performing
        // the action, that they have expired emails
        $expectedEmails = $isResourceAlreadyExpired ? 3 : 4;
        $this->assertEmailQueueCount($expectedEmails);
        $emailDataToOwner = [
            'email' => $ownerWithAccess->username,
            'subject' => h($operator->profile->full_name) . ' marked the password ' . $resourceUpdated->name . ' as expired',
        ];
        if (!$isResourceAlreadyExpired) {
            $this->assertEmailIsInQueue($emailDataToOwner);
        } else {
            $this->assertEmailIsNotInQueue($emailDataToOwner);
        }
    }
}
