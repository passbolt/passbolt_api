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
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Utility\Security;
use Cake\Validation\Validation;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Service\Transfers\TransfersCreateService;

/**
 * TransferCreateService Test Case
 *
 * @covers \Passbolt\Mobile\Service\Transfers\TransfersCreateService
 */
class TransfersCreateServiceTest extends AppTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Base/Users',
    ];

    public function testMobileTransfersCreateService_Success()
    {
        $service = new TransfersCreateService();
        $data = [
            'total_pages' => 1,
            'hash' => Security::hash('test', 'sha512', true),
        ];

        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $transfer = $service->create($data, $accessControl);

        // Check transfer entity
        $this->assertEquals($transfer->user_id, UuidFactory::uuid('user.id.ada'));
        $this->assertNotEmpty(Validation::uuid($transfer->id));
        $this->assertEquals($transfer->total_pages, 1);
        $this->assertEquals($transfer->current_page, 0);
        $this->assertEquals($transfer->status, Transfer::TRANSFER_STATUS_START);
        $this->assertNotEmpty($transfer->created);
        $this->assertNotEmpty($transfer->modified);

        // Check associated auth token
        $this->assertTrue(Validation::uuid($transfer->authentication_token->id));
        $this->assertEquals($transfer->authentication_token->user_id, UuidFactory::uuid('user.id.ada'));
        $this->assertEquals($transfer->authentication_token->type, AuthenticationToken::TYPE_MOBILE_TRANSFER);
        $this->assertTrue($transfer->authentication_token->active);
        $this->assertNotEmpty($transfer->authentication_token->created);
        $this->assertNotEmpty($transfer->authentication_token->modified);
    }

    public function testMobileTransfersCreateService_ValidationError()
    {
        $service = new TransfersCreateService();
        $data = [
            'user_id' => 'nope',
            'total_pages' => 500000000000,
            'current_page' => -1,
            'hash' => 'nope',
        ];
        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        try {
            $service->create($data, $accessControl);
            $this->fail('Expect an exception');
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors);
            $this->assertNotEmpty($errors['hash']['lengthBetween']);
            $this->assertNotEmpty($errors['total_pages']['lessThan']);
            // shouldn't fail / overridden
            $this->assertFalse(isset($errors['user_id']));
            $this->assertFalse(isset($errors['current_page']));

            return;
        }

        $this->fail('Expect a validation exception');
    }

    public function testMobileTransfersCreateService_BuildRulesError()
    {
        $service = new TransfersCreateService();
        $data = [
            'total_pages' => 1,
            'hash' => Security::hash('test', 'sha512', true),
        ];
        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.nope'));
        try {
            $service->create($data, $accessControl);
            $this->fail('Expect an exception');
        } catch (ValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['user_id']['user_exists']);

            return;
        }

        $this->fail('Expect a validation exception');
    }
}
