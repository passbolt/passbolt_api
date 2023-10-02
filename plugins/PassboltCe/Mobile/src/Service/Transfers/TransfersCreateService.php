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

namespace Passbolt\Mobile\Service\Transfers;

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Model\Table\TransfersTable;

/**
 * Class TransfersCreateService
 *
 * @package Passbolt\Mobile\Service\Transfers
 * @property \Passbolt\Mobile\Model\Table\TransfersTable $Transfers
 */
class TransfersCreateService
{
    /**
     * @var \Passbolt\Mobile\Model\Table\TransfersTable
     */
    private $Transfers;

    /**
     * Instantiate the service.
     *
     * @param \Passbolt\Mobile\Model\Table\TransfersTable|null $transfersTable table
     */
    public function __construct(?TransfersTable $transfersTable = null)
    {
        $this->Transfers = $transfersTable ?? TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers');
    }

    /**
     * Create a transfer and the the associated authentication token
     *
     * @param array $data entity data
     * @param \App\Utility\UserAccessControl $uac user access control
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return \Passbolt\Mobile\Model\Entity\Transfer
     */
    public function create(array $data, UserAccessControl $uac): Transfer
    {
        // Check for validation errors
        $transfer = $this->buildTransferEntity($data, $uac);
        if (!empty($transfer->getErrors())) {
            $msg = __('Could not validate the transfer data.');
            throw new ValidationException($msg, $transfer, $this->Transfers);
        }

        // Save and check for build rules errors.
        $transferSaved = $this->Transfers->save($transfer);
        if (!empty($transfer->getErrors())) {
            $msg = __('Could not validate the transfer data.');
            throw new ValidationException($msg, $transfer, $this->Transfers);
        }

        // Check for errors while saving.
        if (!$transferSaved) {
            throw new InternalErrorException(__('The transfer could not be created.'));
        }

        return $transfer;
    }

    /**
     * Return a transfer entity.
     *
     * @param array $data entity data
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return \Passbolt\Mobile\Model\Entity\Transfer
     */
    private function buildTransferEntity(array $data, UserAccessControl $uac): Transfer
    {
        $data['current_page'] = 0;
        $data['status'] = Transfer::TRANSFER_STATUS_START;
        $data['user_id'] = $uac->getId();
        $data['authentication_token'] = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MOBILE_TRANSFER,
        ];

        return $this->Transfers->newEntity($data, [
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
                    ],
                ],
            ],
        ]);
    }
}
