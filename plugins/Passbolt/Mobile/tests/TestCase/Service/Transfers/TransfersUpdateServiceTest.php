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

namespace Passbolt\Mobile\Test\TestCase\Service\Transfers;

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AuthenticationTokenModelTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Service\Transfers\TransfersUpdateService;
use Passbolt\Mobile\Test\Factory\TransferFactory;
use Passbolt\Mobile\Test\Lib\Model\TransfersModelTrait;

/**
 * TransferCreateService Test Case
 *
 * @covers \Passbolt\Mobile\Service\Transfers\TransfersCreateService
 */
class TransfersUpdateServiceTest extends AppTestCase
{
    use AuthenticationTokenModelTrait;
    use UserAccessControlTrait;
    use TransfersModelTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/Users',
    ];

    public function testMobileTransfersUpdateService_Success()
    {
        $transfer = TransferFactory::make()->status(Transfer::TRANSFER_STATUS_IN_PROGRESS)->persist();
        $uac = new UserAccessControl(Role::USER, $transfer->user_id);

        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];

        $service = new TransfersUpdateService();
        $transferOriginal = clone $transfer;
        $transferUpdated = $service->update($transfer, $data, $uac);

        // Unchanged data
        $this->assertTextEquals($transferOriginal->id, $transferUpdated->id);
        $this->assertTextEquals($transferOriginal->user_id, $transferUpdated->user_id);
        $this->assertEquals($transferOriginal->total_pages, $transferUpdated->total_pages);
        $this->assertEquals($transferOriginal->created, $transferUpdated->created);

        // Updated data
        $this->assertNotEquals($transferOriginal->current_page, $transferUpdated->current_page);
        $this->assertNotEquals($transferOriginal->status, $transferUpdated->status);
        $this->assertNotEquals($transferOriginal->modified, $transferUpdated->modified);

        $this->assertEquals($transferUpdated->current_page, 1);
        $this->assertTextEquals($transferUpdated->status, Transfer::TRANSFER_STATUS_COMPLETE);
    }

    public function testMobileTransfersUpdateService_Success_TestTransitions()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $transfer = $this->insertTransferFixture($this->getDummyTransfer(
            $userId,
            Transfer::TRANSFER_STATUS_START,
            1,
            2
        ));

        $service = new TransfersUpdateService();

        // Update - in progress
        $data = [
            'status' => Transfer::TRANSFER_STATUS_IN_PROGRESS,
            'current_page' => 1,
        ];
        $transferUpdated = $service->update($transfer, $data, $uac);
        $this->assertEquals($transferUpdated->current_page, 1);
        $this->assertTextEquals($transferUpdated->status, Transfer::TRANSFER_STATUS_IN_PROGRESS);

        // Update - error
        $data = [
            'status' => Transfer::TRANSFER_STATUS_ERROR,
            'current_page' => 1,
        ];
        $transferUpdated = $service->update($transfer, $data, $uac);
        $this->assertEquals($transferUpdated->current_page, 1);
        $this->assertTextEquals($transferUpdated->status, Transfer::TRANSFER_STATUS_ERROR);

        // Update - completed
        $data = [
            'status' => Transfer::TRANSFER_STATUS_COMPLETE,
            'current_page' => 1,
        ];
        $transferUpdated = $service->update($transfer, $data, $uac);
        $this->assertEquals($transferUpdated->current_page, 1);
        $this->assertTextEquals($transferUpdated->status, Transfer::TRANSFER_STATUS_COMPLETE);
    }

    public function testMobileTransfersCreateService_Error_TransitionsNotAllowed()
    {
        $service = new TransfersUpdateService();
        $transfer = $service->Transfers->newEntity($this->getDummyTransfer(
            UuidFactory::uuid('user.id.ada'),
            Transfer::TRANSFER_STATUS_START,
            0,
            2
        ), ['accessibleFields' => [
            'id' => false,
            'user_id' => true,
            'current_page' => true,
            'total_pages' => true,
            'hash' => true,
            'status' => true,
            'authentication_token' => true,
        ]]);
        $transferUpdated = clone $transfer;

        $fails = [
            // Original -> Updated
            // Cannot update status of completed transfer
            [Transfer::TRANSFER_STATUS_COMPLETE, Transfer::TRANSFER_STATUS_COMPLETE],
            [Transfer::TRANSFER_STATUS_COMPLETE, Transfer::TRANSFER_STATUS_CANCEL],
            [Transfer::TRANSFER_STATUS_COMPLETE, Transfer::TRANSFER_STATUS_IN_PROGRESS],
            [Transfer::TRANSFER_STATUS_COMPLETE, Transfer::TRANSFER_STATUS_ERROR],
            [Transfer::TRANSFER_STATUS_COMPLETE, Transfer::TRANSFER_STATUS_START],
            // Cannot update status of cancelled transfer
            [Transfer::TRANSFER_STATUS_CANCEL, Transfer::TRANSFER_STATUS_COMPLETE],
            [Transfer::TRANSFER_STATUS_CANCEL, Transfer::TRANSFER_STATUS_CANCEL],
            [Transfer::TRANSFER_STATUS_CANCEL, Transfer::TRANSFER_STATUS_IN_PROGRESS],
            [Transfer::TRANSFER_STATUS_CANCEL, Transfer::TRANSFER_STATUS_ERROR],
            [Transfer::TRANSFER_STATUS_CANCEL, Transfer::TRANSFER_STATUS_START],
            // Cannot restart
            [Transfer::TRANSFER_STATUS_ERROR, Transfer::TRANSFER_STATUS_START],
            [Transfer::TRANSFER_STATUS_IN_PROGRESS, Transfer::TRANSFER_STATUS_START],
            // Cannot complete without being on last page
            [Transfer::TRANSFER_STATUS_IN_PROGRESS, Transfer::TRANSFER_STATUS_COMPLETE],
        ];

        foreach ($fails as $i => $failTuple) {
            $transfer->status = $failTuple[0];
            $transferUpdated->status = $failTuple[1];
            try {
                $service->assertTransitionAllowed($transfer, $transferUpdated);
                $this->fail('Expect an exception');
            } catch (ForbiddenException $exception) {
                $this->assertTrue(true);
            } catch (\Exception $exception) {
                $msg = __('Expect a forbidden exception for assertion {0} {1} {2}', $i, $failTuple[0], $failTuple[1]);
                $this->fail($msg);
            }
        }
    }

    public function testMobileTransfersCreateService_Error_TransitionsNotAllowed_CurrentPageBiggerThanTotal()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);
        $transfer = $this->insertTransferFixture($this->getDummyTransfer(
            $userId,
            Transfer::TRANSFER_STATUS_START,
            1,
            2
        ));

        $service = new TransfersUpdateService();

        // Update - in progress
        $data = [
            'status' => Transfer::TRANSFER_STATUS_IN_PROGRESS,
            'current_page' => 2,
        ];
        $this->expectException(ValidationException::class);
        $service->update($transfer, $data, $uac);
    }

    public function testMobileTransfersUpdateService_Error_ValidationError()
    {
        $transfer = TransferFactory::make()->status(Transfer::TRANSFER_STATUS_IN_PROGRESS)->persist();
        $service = new TransfersUpdateService();
        $uac = new UserAccessControl(Role::USER, $transfer->user_id);
        $data = [];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Could not validate the transfer data.');
        $service->update($transfer, $data, $uac);
    }

    public function testMobileTransfersUpdateService_Error_ExpiredAuthToken()
    {
        $userId = UuidFactory::uuid();
        $transfer = TransferFactory::make()
            ->status(Transfer::TRANSFER_STATUS_IN_PROGRESS)
            ->userId($userId)
            ->withAuthenticationToken(
                AuthenticationTokenFactory::make()
                    ->type(AuthenticationToken::TYPE_MOBILE_TRANSFER)
                    ->userId($userId)
                    ->active()
                    ->expired()
            )
            ->persist();

        $service = new TransfersUpdateService();
        $uac = new UserAccessControl(Role::USER, $transfer->user_id);
        $data = [];

        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('The authentication token is expired.');
        $service->update($transfer, $data, $uac);
    }
}
