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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use Cake\Utility\Security;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

class TransfersUpdateControllerTest extends AppIntegrationTestCase
{
    use AuthenticationTokenModelTrait;
    use ProfilesModelTrait;
    use TransfersModelTrait;
    use UsersModelTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Roles',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('mobile');
    }

    public function testMobileTransfersUpdateController_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json", $data);

        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertFalse(isset($this->_responseJsonBody->authentication_token));
        $this->assertFalse(isset($this->_responseJsonBody->user));
    }

    public function testMobileTransfersUpdateController_SuccessWithContainAvatar()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json?contain[user.profile]=1", $data);

        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertFalse(isset($this->_responseJsonBody->authentication_token));
        $this->assertUserAttributes($this->_responseJsonBody->user);
        $this->assertProfileAttributes($this->_responseJsonBody->user->profile);
        $this->assertObjectHasAttributes(['small', 'medium'], $this->_responseJsonBody->user->profile->avatar->url);
    }

    public function testMobileTransfersUpdateController_ErrorNoData()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json", []);

        $this->assertError(400);
    }

    public function testMobileTransfersUpdateController_ErrorTransferDoesNotBelongToUser()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAsUser(); // Login with another user.

        $this->postJson("/mobile/transfers/$id.json", $data);

        $this->assertError(403);
    }

    public function testMobileTransfersUpdateController_ErrorEmptyData()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $data = [];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json", $data);

        $this->assertError(400);
    }

    public function testMobileTransfersUpdateController_ErrorNotAuthenticated()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->postJson("/mobile/transfers/$id.json", $data);
        $this->assertAuthenticationError();
    }

    public function testMobileTransfersUpdateController_ErrorTransferFordbidden_TokenInactive()
    {
        $transfer = $this->insertTransferFixture([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'current_page' => 1,
            'status' => Transfer::TRANSFER_STATUS_IN_PROGRESS,
            'total_pages' => 2,
            'hash' => Security::hash('test', 'sha512', true),
            'authentication_token' => [
                'user_id' => UuidFactory::uuid('user.id.ada'),
                'token' => UuidFactory::uuid(),
                'active' => false,
                'type' => AuthenticationToken::TYPE_MOBILE_TRANSFER,
            ],
        ]);
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->postJson("/mobile/transfers/$id.json", $data);
        $this->assertAuthenticationError();
    }

    public function testMobileTransfersUpdateController_ErrorTransferForbidden_TokenExpired()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture([
            'user_id' => $user->id,
            'current_page' => 1,
            'status' => Transfer::TRANSFER_STATUS_IN_PROGRESS,
            'total_pages' => 2,
            'hash' => Security::hash('test', 'sha512', true),
            'authentication_token' => [
                'user_id' => $user->id,
                'token' => UuidFactory::uuid(),
                'active' => true,
                'type' => AuthenticationToken::TYPE_MOBILE_TRANSFER,
                'created' => new FrozenDate('last year'),
            ],
        ]);
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json", $data);

        $this->assertError(403);
    }

    public function testMobileTransfersUpdateController_ErrorTransferForbidden_TokenWrongType()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture([
            'user_id' => $user->id,
            'current_page' => 1,
            'status' => Transfer::TRANSFER_STATUS_IN_PROGRESS,
            'total_pages' => 2,
            'hash' => Security::hash('test', 'sha512', true),
            'authentication_token' => [
                'user_id' => $user->id,
                'token' => UuidFactory::uuid(),
                'active' => true,
                'type' => AuthenticationToken::TYPE_RECOVER,
                'created' => new FrozenDate('last year'),
            ],
        ]);
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id.json", $data);

        $this->assertError(403);
    }

    /**
     * No session but with auth token instead.
     */
    public function testMobileTransfersUpdateController_NoSession_Success()
    {
        $user = UserFactory::make()->user()->persist();
        $transfer = $this->insertTransferFixture($this->getDummyTransfer($user->id));
        $id = $transfer->id;
        $token = $transfer->authentication_token->token;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->logInAs($user);

        $this->postJson("/mobile/transfers/$id/$token.json", $data);

        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertTrue(!isset($this->_responseJsonBody->authentication_token));
    }

    public function testMobileTransfersUpdateController_NoSession_ErrorInvalidToken()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $token = UuidFactory::uuid('nope');
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->postJson("/mobile/transfers/$id/$token.json", $data);
        $this->assertError(401);
    }

    public function testMobileTransfersUpdateController_NoSession_ErrorInvalidToken2()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $this->postJson("/mobile/transfers/$id/nope.json", $data);
        $this->assertError(401);
    }
}
