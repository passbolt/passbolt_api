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
 * @since         3.3.0
 */
namespace Passbolt\Mobile\Test\TestCase\Controller\Transfers;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Utility\UuidFactory;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

class TransfersViewControllerTest extends AppIntegrationTestCase
{
    use ProfilesModelTrait;
    use TransfersModelTrait;
    use UsersModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('mobile');
    }

    public function testMobileTransfersViewController_Success()
    {
        $user = $this->logInAsUser();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $this->getJson("/mobile/transfers/$id.json");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertFalse(isset($this->_responseJsonBody->user));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile->avatar));
    }

    public function testMobileTransfersViewController_SuccessWithContainUser()
    {
        $user = $this->logInAsUser();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $this->getJson("/mobile/transfers/$id.json?contain[user]=1");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->user);
        $this->assertFalse(isset($this->_responseJsonBody->user->profile));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile->avatar));
    }

    public function testMobileTransfersViewController_SuccessWithContainProfileAvatar()
    {
        $user = $this->logInAsUser();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $this->getJson("/mobile/transfers/$id.json?contain[user]=1&contain[user.profile]=1");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->user);
        $this->assertProfileAttributes($this->_responseJsonBody->user->profile);
        $this->assertObjectHasAttributes(['small', 'medium'], $this->_responseJsonBody->user->profile->avatar->url);
    }

    public function testMobileTransfersViewController_ErrorNotFound()
    {
        $this->logInAsUser();
        $id = UuidFactory::uuid('nope');
        $this->getJson("/mobile/transfers/$id.json?api-version=2");
        $this->assertError(404);
    }

    /**
     * If not the owner of the transfer, cannot view it.
     */
    public function testMobileTransfersViewController_ErrorNotOwner()
    {
        [$user, $owner] = UserFactory::make(2)->user()->active()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($owner->id));
        $this->logInAs($user);

        $id = $transfer->id;
        $this->getJson("/mobile/transfers/$id.json?api-version=2");
        $this->assertError(404);
    }

    public function testMobileTransfersViewController_ErrorWrongUuidParameter()
    {
        $this->logInAsUser();
        $this->getJson('/mobile/transfers/not_uuid.json?api-version=2');
        $this->assertError(400);
    }

    public function testMobileTransfersViewController_ErrorNotAuthenticated()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;

        $this->getJson("/mobile/transfers/$id.json");
        $this->assertAuthenticationError();
    }
}
