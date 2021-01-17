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
 * @since         3.1.0
 */
namespace Passbolt\Mobile\Test\TestCase\Controller\Transfers;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

class TransfersViewControllerTest extends AppIntegrationTestCase
{
    use TransfersModelTrait;

    public $fixtures = [
        'app.Base/Users',
    ];

    public function testMobileTransfersViewController_Success()
    {
        $transfer = $this->insertTransferFixture($this->getDummyTransfer());
        $id = $transfer->id;
        $this->authenticateAs('ada');
        $this->getJson("/mobile/transfers/$id.json");
        $this->assertSuccess();
        $this->assertTransferAttributes($this->_responseJsonBody);
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
