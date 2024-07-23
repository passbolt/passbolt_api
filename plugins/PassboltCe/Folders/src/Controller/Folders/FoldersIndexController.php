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
use App\Database\Type\ISOFormatDateTimeType;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;

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
        $this->Folders = $this->fetchTable('Passbolt/Folders.Folders');
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

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $folders = $this->Folders->findIndex($this->User->id(), $options);
        $folders->disableHydration();
        $this->paginate($folders);
        $folders = $folders->all();
        $folders = FolderizableBehavior::unsetPersonalPropertyIfNullOnResultSet($folders);
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();
        $folders = $this->removeJoinDataFromResults($folders->toArray(), $options);

        $this->success(__('The operation was successful.'), $folders);
    }

    /**
     * @param array $folders folders paginated
     * @param array $options options passed in the request
     * @return array
     */
    private function removeJoinDataFromResults(array $folders, array $options): array
    {
        // Since hydration is disabled, the ResultSet skips the creation of entity classes, and hidden fields are not hidden anymore
        // When belongsToMany associations are contained, we remove here the _joinData needed for Cake to build the result set.
        // This cannot be made at the formatResult level.
        $containsChildrenFolder = $options['contain']['children_folders'] ?? false;
        $containsChildrenResources = $options['contain']['children_resources'] ?? false;
        if ($containsChildrenFolder || $containsChildrenResources) {
            $folders = Hash::remove($folders, '{n}.{s}.{n}._joinData');
        }

        return $folders;
    }
}
