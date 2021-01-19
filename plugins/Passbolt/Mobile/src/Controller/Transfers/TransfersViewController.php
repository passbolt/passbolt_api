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

namespace Passbolt\Mobile\Controller\Transfers;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

/**
 * @property \Passbolt\Mobile\Model\Table\TransfersTable Transfers
 */
class TransfersViewController extends AppController
{
    /**
     * View a transfer status
     *
     * @param string $id transfer uuid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if transfer does not exist
     * @return void
     */
    public function view(string $id): void
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The transfer id is not valid.'));
        }

        $this->loadModel('Passbolt/Mobile.Transfers');
        $transfer = $this->Transfers->get($id);

        $this->success(__('The operation was successful.'), $transfer);
    }
}
