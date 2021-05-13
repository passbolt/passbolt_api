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
namespace Passbolt\Mobile\Controller\Transfers;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Mobile\Service\Transfers\TransfersCreateService;

/**
 * Class TransfersCreateController
 *
 * @package Passbolt\Mobile\Controller\Transfers
 * @property \Passbolt\Mobile\Model\Table\TransfersTable $Transfers
 */
class TransfersCreateController extends AppController
{
    /**
     * Start a transfer
     *
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return void
     */
    public function create(): void
    {
        $data = $this->request->getData();
        if (!isset($data) || empty($data) || !is_array($data)) {
            throw new BadRequestException(__('Information about the transfer is required.'));
        }

        $uac = $this->User->getAccessControl();
        $createService = new TransfersCreateService();
        $transfer = $createService->create($data, $uac);

        $this->success(__('The operation was successful'), $transfer);
    }
}
