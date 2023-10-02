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
use App\Model\Table\AuthenticationTokensTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Mobile\Model\Entity\Transfer;
use Passbolt\Mobile\Model\Table\TransfersTable;

/**
 * Class TransfersUpdateService
 *
 * @package Passbolt\Mobile\Service\Transfers
 */
class TransfersUpdateService
{
    /**
     * @var \App\Model\Table\AuthenticationTokensTable table
     */
    public $AuthenticationTokens;

    /**
     * @var \Passbolt\Mobile\Model\Table\TransfersTable table
     */
    public $Transfers;

    /**
     * Instantiate the service.
     *
     * @param \Passbolt\Mobile\Model\Table\TransfersTable|null $transfersTable table
     * @param \App\Model\Table\AuthenticationTokensTable|null $authTable table
     */
    public function __construct(?TransfersTable $transfersTable = null, ?AuthenticationTokensTable $authTable = null)
    {
        $this->Transfers = $transfersTable ?? TableRegistry::getTableLocator()->get('Passbolt/Mobile.Transfers');
        $this->AuthenticationTokens = $authTable ?? TableRegistry::getTableLocator()->get('AuthenticationTokens');

        // Cleanup the tokens if needed
        // @later (tm) could be moved in a cron job
        $this->AuthenticationTokens->setActiveExpiredTokenToInactive(AuthenticationToken::TYPE_MOBILE_TRANSFER);
        $this->Transfers->cancelAllTransfersWithInactiveAuthenticationToken();
    }

    /**
     * Create a transfer and the the associated authentication token
     *
     * @param \Passbolt\Mobile\Model\Entity\Transfer $transfer entity
     * @param array $data entity data
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return \Passbolt\Mobile\Model\Entity\Transfer
     */
    public function update(Transfer $transfer, array $data, UserAccessControl $uac): Transfer
    {
        $this->assertOperationIsAllowed($transfer, $uac);

        // Check for validation errors
        $originalTransfer = clone $transfer;
        $transfer = $this->patchTransferEntity($transfer, $data);
        $this->assertTransitionAllowed($originalTransfer, $transfer);

        if (!empty($transfer->getErrors())) {
            $msg = __('Could not validate the transfer data.');
            throw new ValidationException($msg, $transfer, $this->Transfers);
        }

        // Save and check for application rules errors.
        $transferSaved = $this->Transfers->save($transfer);
        if (!empty($transfer->getErrors())) {
            $msg = __('Could not update the transfer data.');
            throw new ValidationException($msg, $transfer, $this->Transfers);
        }

        // Check for errors while saving.
        if (!$transferSaved) {
            throw new InternalErrorException(__('The transfer could not be updated.'));
        }

        return $transfer;
    }

    /**
     * Validate possible transitions
     * TODO transform into build rules?
     *
     * @param \Passbolt\Mobile\Model\Entity\Transfer $original entity
     * @param \Passbolt\Mobile\Model\Entity\Transfer $updated entity
     * @return void
     */
    public function assertTransitionAllowed(Transfer $original, Transfer $updated): void
    {
        // Cannot "restart"
        if ($updated->status === Transfer::TRANSFER_STATUS_START) {
            $msg = __('This operation is not allowed.') . ' ';
            $msg .= __('Restarting a transfer is not allowed.');
            throw new ForbiddenException($msg);
        }

        // Cannot update a cancelled or completed transfer
        if (
            ($updated->status === Transfer::TRANSFER_STATUS_IN_PROGRESS
                || $updated->status === Transfer::TRANSFER_STATUS_CANCEL
                || $updated->status === Transfer::TRANSFER_STATUS_COMPLETE
                || $updated->status === Transfer::TRANSFER_STATUS_ERROR)
            &&
            ($original->status === Transfer::TRANSFER_STATUS_CANCEL
                || $original->status === Transfer::TRANSFER_STATUS_COMPLETE)
        ) {
            $msg = __('This operation is not allowed.') . ' ';
            $msg .= __('The operation is already over.');
            throw new ForbiddenException($msg);
        }

        // Cannot "complete" without being on last page
        if (
            $updated->status === Transfer::TRANSFER_STATUS_COMPLETE &&
            $updated->current_page !== $original->total_pages - 1
        ) {
            $msg = __('This operation is not allowed.') . ' ';
            $msg .= __('The current page does not match the total number of pages.');
            throw new ForbiddenException($msg);
        }
    }

    /**
     * Check if operation is allowed
     *
     * @param \Passbolt\Mobile\Model\Entity\Transfer $transfer entity
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @throws \Cake\Http\Exception\ForbiddenException if operation is not allowed for example:
     * - Transfer or AuthToken is for another user
     * - Authentication token is expired
     * @return void
     */
    private function assertOperationIsAllowed(Transfer $transfer, UserAccessControl $uac): void
    {
        if ($transfer->user_id !== $uac->getId()) {
            throw new ForbiddenException(__('This operation is not allowed for this user.'));
        }
        if (!isset($transfer->authentication_token)) {
            throw new ForbiddenException(__('The authentication token is missing.'));
        }
        if ($transfer->authentication_token->user_id !== $uac->getId()) {
            throw new ForbiddenException(__('The authentication token is not valid for this user.'));
        }
        if ($transfer->authentication_token->type !== AuthenticationToken::TYPE_MOBILE_TRANSFER) {
            throw new ForbiddenException(__('The authentication token type is invalid.'));
        }
        if ($transfer->authentication_token->active !== true) {
            throw new ForbiddenException(__('The authentication token is not active.'));
        }
        if ($transfer->authentication_token->isExpired()) {
            throw new ForbiddenException(__('The authentication token is expired.'));
        }
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
        $data['total_pages'] = $transfer->total_pages;

        return $this->Transfers->patchEntity($transfer, $data, [
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
