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

/**
 * @property \Passbolt\Folders\Model\Table\FoldersTable $Folders
 */
class FoldersIndexController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'Folders',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'Folders.name',
            'Folders.created',
            'Folders.modified',
        ],
        'order' => [
            'Folders.name' => 'asc', // Default sorted field
        ],
    ];

    /**
     * Folders Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Passbolt/Folders.Folders');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'children_resources',
                'children_folders',
                'creator',
                'creator.profile',
                'modifier',
                'modifier.profile',
                'permission',
                'permissions',
                'permissions.user.profile',
                'permissions.group',
            ],
            'filter' => [
                'has-id',
                'has-parent',
                'search',
            ],
        ];
        $options = $this->QueryString->get($whitelist);

        $resources = $this->Folders->findIndex($this->User->id(), $options);
        $this->paginate($resources);
        $this->success(__('The operation was successful.'), $resources);
    }
}
