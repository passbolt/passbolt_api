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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */

namespace Passbolt\Mobile\Service\Transfers;

use App\Error\Exception\ValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Model\Table\TransfersTable;

class TransfersUpdateService
{
    /**
     * @var \Passbolt\Mobile\Model\Table\TransfersTable
     */
    private $transfersTable;

    /**
     * Instantiate the service.
     *
     * @param \Passbolt\Mobile\Model\Table\TransfersTable|null $transfersTable table
     */
    public function __construct(?TransfersTable $transfersTable = null)
    {
        $this->transfersTable = $transfersTable ?? TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers');
    }

    /**
     * Create a transfer and the the associated authentication token
     *
     * @param \Passbolt\Mobile\Model\Entity\Transfer $transfer entity
     * @param array $data entity data
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return \Passbolt\Mobile\Model\Entity\Transfer
     */
    public function update(Transfer $transfer, array $data): Transfer
    {
        // Check for validation errors
        $transfer = $this->patchTransferEntity($transfer, $data);
        if (!empty($transfer->getErrors())) {
            $msg = __('Could not validate the transfer data.');
            throw new ValidationException($msg, $transfer, $this->transfersTable);
        }

        // Save and check for build rules errors.
        $transferSaved = $this->transfersTable->save($transfer);
        if (!empty($transfer->getErrors())) {
            $msg = __('Could not update the transfer data.');
            throw new ValidationException($msg, $transfer, $this->transfersTable);
        }

        // Check for errors while saving.
        if (!$transferSaved) {
            throw new InternalErrorException(__('The transfer could not be updated.'));
        }

        return $transfer;
    }

    /**
     * Return an updated transfer entity.
     *
     * @param \Passbolt\Mobile\Model\Entity\Transfer $transfer entity
     * @param array $data data
     * @return \Passbolt\Mobile\Model\Entity\Transfer
     */
    private function patchTransferEntity(Transfer $transfer, array $data): Transfer
    {
        return $this->transfersTable->patchEntity($transfer, $data, [
            'accessibleFields' => [
                'id' => true,
                'user_id' => false,
                'current_page' => true,
                'total_pages' => false,
                'hash' => false,
                'status' => true,
                'authentication_token' => false,
                'authentication_token_id' => false,
            ],
        ]);
    }
}
