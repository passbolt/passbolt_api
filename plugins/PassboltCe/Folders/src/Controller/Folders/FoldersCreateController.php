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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Controller\Folders;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Service\Folders\FoldersCreateService;

class FoldersCreateController extends AppController
{
    /**
     * Folders create action.
     *
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $uac = $this->User->getAccessControl();
        $data = $this->getData();
        $folderCreateService = new FoldersCreateService();

        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = $folderCreateService->create($uac, $data);

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'children_folders',
                'children_resources',
                'creator',
                'modifier',
                'permission',
                'permissions',
                'permissions.group',
                'permissions.user.profile',
            ],
        ];
        $options = $this->QueryString->get($whitelist);
        $folder = $folderCreateService->foldersTable->findView($this->User->id(), $folder->id, $options)->first();
        $folder = FolderizableBehavior::unsetPersonalPropertyIfNull($folder->toArray());

        $this->success(__('The folder has been added successfully.'), $folder);
    }

    /**
     * Extract data from the request body.
     *
     * @return array
     */
    private function getData()
    {
        $data = [];
        $body = $this->getRequest()->getParsedBody();

        $name = Hash::get($body, 'name');
        if (isset($name)) {
            $data['name'] = $name;
        }

        $folderParentId = Hash::get($body, 'folder_parent_id');
        if (isset($folderParentId)) {
            $data['folder_parent_id'] = $folderParentId;
        }

        return $data;
    }
}
