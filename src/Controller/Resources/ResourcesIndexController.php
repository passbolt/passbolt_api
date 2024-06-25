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
 * @since         2.0.0
 */

namespace App\Controller\Resources;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;

/**
 * @property \BryanCrowe\ApiPagination\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class ResourcesIndexController extends AppController
{
    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'Resources',
        ]);
        $this->Resources = $this->fetchTable('Resources');
    }

    public $paginate = [
        'sortableFields' => [
            'Resources.name',
            'Resources.username',
            'Resources.uri',
            'Resources.modified',
        ],
        'order' => [
            'Resources.name' => 'asc', // Default sorted field
        ],
    ];

    /**
     * Resource Index action
     *
     * @return void
     */
    public function index()
    {
        $this->assertJson();

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'creator', 'favorite', 'modifier', 'secret', 'resource-type',
                'permission', 'permissions', 'permissions.user.profile', 'permissions.group',
            ],
            'filter' => ['is-favorite', 'is-shared-with-group', 'is-owned-by-me', 'is-shared-with-me', 'has-id'],
        ];

        if (Configure::read('passbolt.plugins.tags')) {
            $whitelist['contain'][] = 'tag'; // @deprecate should be tags
            $whitelist['filter'][] = 'has-tag';
        }
        if (Configure::read('passbolt.plugins.folders')) {
            $whitelist['filter'][] = 'has-parent';
        }
        $options = $this->QueryString->get($whitelist);

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $resources = $this->Resources->findIndex($this->User->id(), $options)->disableHydration();
        $this->paginate($resources);
        $resources = $resources->all();
        $resources = FolderizableBehavior::unsetPersonalPropertyIfNullOnResultSet($resources);
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();
        $this->_logSecretAccesses($resources, $options);
        $this->success(__('The operation was successful.'), $resources);
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param \Cake\Collection\CollectionInterface $resources resources
     * @param array $queryOptions The query options
     * @return void
     */
    protected function _logSecretAccesses(CollectionInterface $resources, array $queryOptions)
    {
        $containSecret = (bool)Hash::get($queryOptions, 'contain.secret');
        if (!$containSecret) {
            return;
        }

        if (!$this->Resources->getAssociation('Secrets')->hasAssociation('SecretAccesses')) {
            return;
        }

        foreach ($resources as $resource) {
            $secrets = Hash::get($resource, 'secrets');
            if (!isset($secrets)) {
                continue;
            }

            foreach ($secrets as $secret) {
                try {
                    $this->Resources->Secrets->SecretAccesses->createFromSecretDetails(
                        $this->User->getAccessControl(),
                        Hash::get($secret, 'resource_id'),
                        Hash::get($secret, 'id'),
                    );
                } catch (\Exception $e) {
                    throw new InternalErrorException('Could not log secret access entry.', 500, $e);
                }
            }
        }
    }
}
