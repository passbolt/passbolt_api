<?php
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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Controller\Folders;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Folders\Service\FoldersDeleteService;

class FoldersDeleteController extends AppController
{
    /**
     * Folders delete action
     *
     * @param string $id The identifier of the folder.
     * @return void
     */
    public function delete(string $id)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The folder id is not valid.'));
        }

        $uac = $this->User->getAccessControl();
        $foldersDeleteService = new FoldersDeleteService();

        $foldersDeleteService->delete($uac, $id);

        $this->success(__('The folder has been deleted successfully.'));
    }

}
