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

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Utility\UuidFactory;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

class TransfersViewControllerTest extends AppIntegrationTestCase
{
    use TransfersModelTrait;
    use UsersModelTrait;
    use ProfilesModelTrait;
    use AvatarsModelTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
    ];

    public function testMobileTransfersViewController_Success()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $this->authenticateAs('ada');
        $this->getJson("/mobile/transfers/$id.json");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertFalse(isset($this->_responseJsonBody->user));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile->avatar));
    }

    public function testMobileTransfersViewController_SuccessWithContainUser()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $this->authenticateAs('ada');
        $this->getJson("/mobile/transfers/$id.json?contain[user]=1");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->user);
        $this->assertFalse(isset($this->_responseJsonBody->user->profile));
        $this->assertFalse(isset($this->_responseJsonBody->user->profile->avatar));
    }

    public function testMobileTransfersViewController_SuccessWithContainProfileAvatar()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $this->authenticateAs('ada');
        $this->getJson("/mobile/transfers/$id.json?contain[user]=1&contain[user.profile]=1");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->user);
        $this->assertProfileAttributes($this->_responseJsonBody->user->profile);
        $this->assertAvatarAttributes($this->_responseJsonBody->user->profile->avatar);
    }

    public function testMobileTransfersViewController_ErrorNotFound()
    {
        $this->authenticateAs('ada');
        $id = UuidFactory::uuid('nope');
        $this->getJson("/mobile/transfers/$id.json?api-version=2");
        $this->assertError(404);
    }

    public function testMobileTransfersViewController_ErrorWrongUuidParameter()
    {
        $this->authenticateAs('ada');
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
