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
namespace Passbolt\Mobile\Test\Lib\Model;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Passbolt\Mobile\Model\Entity\Transfer;

trait TransfersModelTrait
{
    /**
     * Insert a dummy transfer fixture in database
     *
     * @return Transfer
     */
    public function insertDummyTransferFixture(): Transfer
    {
        return $this->insertTransferFixture($this->getDummyTransfer());
    }

    /**
     * @param string|null $user
     * @param string|null $status
     * @param int|null $currentPage
     * @param int|null $totalPages
     * @return array
     */
    public function getDummyTransfer(
        ?string $userId = null,
        ?string $status = Transfer::TRANSFER_STATUS_IN_PROGRESS,
        ?int $currentPage = 1,
        ?int $totalPages = 2
    ): array {
        $userId = $userId ?? UuidFactory::uuid('user.id.ada');

        return [
            'user_id' => $userId,
            'current_page' => $currentPage,
            'status' => $status,
            'total_pages' => $totalPages,
            'hash' => Security::hash('test', 'sha512', true),
            'authentication_token' => [
                'user_id' => $userId,
                'token' => UuidFactory::uuid(),
                'active' => true,
                'type' => AuthenticationToken::TYPE_MOBILE_TRANSFER,
            ],
        ];
    }

    /**
     * Create a transfer fixture
     * The transfer data must pass a default validation.
     *
     * @param array|null $data Custom data that will be merged with the default dummy comment.
     * @return Transfer transfer entity
     */
    public function insertTransferFixture(?array $data = []): Transfer
    {
        /** @var \Passbolt\Mobile\Model\Table\TransfersTable $transfersTable */
        $transfersTable = TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers');
        /** @var \Passbolt\Mobile\Model\Entity\Transfer $transfer */
        $transfer = $transfersTable->newEntity($data, $this->getTransferEntityAccessibleFields());

        $transfersTable->save($transfer, ['checkRules' => false]);

        return $transfer;
    }

    /**
     * @return array accessibleFields
     */
    protected function getTransferEntityAccessibleFields()
    {
        return [
            'accessibleFields' => [
                'id' => false,
                'user_id' => true,
                'current_page' => true,
                'total_pages' => true,
                'hash' => true,
                'status' => true,
                'authentication_token' => true,
            ],
            'associated' => [
                'AuthenticationTokens' => [
                    'accessibleFields' => [
                        'user_id' => true,
                        'token' => true,
                        'active' => true,
                        'type' => true,
                        'created' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Asserts that an object has all the attributes a transfer should have.
     *
     * @param object $transfer
     */
    protected function assertTransferAttributes($transfer)
    {
        $attributes = [
            'id', 'user_id', 'current_page', 'total_pages', 'hash', 'status',
        ];
        $this->assertObjectHasAttributes($attributes, $transfer);
    }
}
