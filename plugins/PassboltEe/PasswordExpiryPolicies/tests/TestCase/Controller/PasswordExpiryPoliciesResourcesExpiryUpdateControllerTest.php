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

namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Controller;

use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiry\Notification\Email\PasswordExpiryPasswordMarkedExpiredEmailRedactor;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Lib\PasswordExpiryTestTrait;
use Passbolt\PasswordExpiryPolicies\PasswordExpiryPoliciesPlugin;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordExpiry\Controller\PasswordExpirySettingsGetController
 */
class PasswordExpiryPoliciesResourcesExpiryUpdateControllerTest extends AppIntegrationTestCase
{
    use PasswordExpiryTestTrait;
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        $this->enableFeaturePlugin(PasswordExpiryPoliciesPlugin::class);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateController_Success_Expire_Resources()
    {
        RoleFactory::make()->guest()->persist();
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\User $otherOwner */
        $otherOwner = UserFactory::make()->withAvatar()->persist();

        $group = GroupFactory::make()
            ->withGroupsUsersFor([$user])
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceShared */
        $resourceShared = ResourceFactory::make('Resource shared with other user')
            ->withPermissionsFor([$user, $otherOwner])
            ->expired(FrozenTime::today()->addDays(3))
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceNotShared */
        $resourceNotShared = ResourceFactory::make('Resource not shared with other user')
            ->withPermissionsFor([$group])
            ->expired(FrozenTime::today()->addDays(3))
            ->persist();
        $newExpiryDateInThePast = FrozenTime::today()->subDays(3)->toAtomString();
        $payload = [
            ['id' => $resourceShared->get('id'), 'expired' => $newExpiryDateInThePast],
            ['id' => $resourceNotShared->get('id'), 'expired' => $newExpiryDateInThePast],
        ];

        $this->postJson('/password-expiry/resources.json', $payload);
        $this->assertSuccess();

        $this->assertSame(2, count($this->_responseJsonBody));
        foreach ($this->_responseJsonBody as $resource) {
            $resource = json_decode(json_encode($resource), true);
            $this->assertSame($resource['expired'], $newExpiryDateInThePast);
            $this->assertSame($resource['modified_by'], $user->get('id'));
        }

        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'You edited the password ' . $resourceShared->get('name'),
            'template' => ResourceUpdateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailIsInQueue([
            'email' => $otherOwner->username,
            'subject' => $user->profile->first_name . ' edited the password ' . $resourceShared->get('name'),
            'template' => ResourceUpdateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'You edited the password ' . $resourceNotShared->get('name'),
            'template' => ResourceUpdateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => 'You edited the password ' . $resourceNotShared->get('name'),
            'template' => ResourceUpdateEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailIsInQueue([
            'email' => $otherOwner->username,
            'subject' => h($user->profile->full_name) . ' marked the password ' . $resourceShared->name . ' as expired',
            'template' => PasswordExpiryPasswordMarkedExpiredEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailQueueCount(4);
    }

    public function testPasswordExpiryPoliciesResourcesExpiryUpdateController_Success_Expire_Resources_In_Future()
    {
        RoleFactory::make()->guest()->persist();
        PasswordExpiryPoliciesSettingFactory::make()->persist();
        $user = $this->logInAsUser();

        /** @var \App\Model\Entity\Resource $resourceNotShared */
        $resourceWithExpiredNull = ResourceFactory::make('Resource not expired')
            ->withPermissionsFor([$user])
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceNotShared */
        $resourceExpiredInFuture = ResourceFactory::make('Resource expired in future')
            ->withPermissionsFor([$user])
            ->expired(FrozenTime::tomorrow())
            ->persist();
        $newExpiryDateInTheFuture = FrozenTime::today()->addDays(3)->toAtomString();
        $payload = [
            ['id' => $resourceWithExpiredNull->get('id'), 'expired' => $newExpiryDateInTheFuture],
            ['id' => $resourceExpiredInFuture->get('id'), 'expired' => $newExpiryDateInTheFuture],
        ];

        $this->postJson('/password-expiry/resources.json', $payload);
        $this->assertSuccess();

        $this->assertSame(2, count($this->_responseJsonBody));
        foreach ($this->_responseJsonBody as $i => $resource) {
            $resource = json_decode(json_encode($resource), true);
            $this->assertSame($resource['expired'], $newExpiryDateInTheFuture);
            $this->assertSame($resource['modified_by'], $user->get('id'));
            $this->assertEmailInBatchContains('You edited the password ' . $resource['name'], $i);
        }
        $this->assertEmailInBatchNotContains('This resource is not expired anymore.', 0);
        $this->assertEmailInBatchNotContains('This resource is now set as expired.', 0);
        $this->assertEmailInBatchNotContains('This resource is not expired anymore.', 1);
        $this->assertEmailInBatchNotContains('This resource is now set as expired.', 1);
        $this->assertEmailQueueCount(2);
    }
}
