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

namespace Passbolt\Mobile\Test\TestCase\Model\Table\Transfers;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Model\Table\TransfersTable;
use Passbolt\Mobile\Test\Factory\TransferFactory;

class CancelAllTransfersWithInactiveAuthenticationTokenTest extends AppTestCase
{
    /**
     * @var TransfersTable $Transfers
     */
    public $Transfers;
    public $autoFixtures = false;
    public $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Passbolt/Mobile.Transfers') ?
            [] : ['className' => TransfersTable::class];
        $this->Transfers = TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers', $config);
    }

    public function testTransfersTablet_IN_PROGRESS(): void
    {
        $this->markTestSkipped();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_MOBILE_TRANSFER)
            ->active()
            ->expired();

        TransferFactory::make()
            ->status(Transfer::TRANSFER_STATUS_START)
            ->withAuthenticationToken($token)
            ->persist();

        $found = $this->Transfers->find()->contain(['AuthenticationTokens'])->all();
    }
}
