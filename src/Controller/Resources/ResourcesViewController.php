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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;

/**
 * ResourcesViewController Class
 */
class ResourcesViewController extends AppController
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
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * Resource View action
     *
     * @param string $id uuid Identifier of the resource
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @return void
     */
    public function view(string $id): void
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        // Retrieve and sanity the query options.
        $whitelist = ['contain' => [
            'creator', 'favorite', 'modifier', 'secret', 'resource-type',
            'permission', 'permissions', 'permissions.user.profile', 'permissions.group',
        ]];
        $options = $this->QueryString->get($whitelist);

        // Retrieve the resource.
        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->findView($this->User->id(), $id, $options)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        $resource = FolderizableBehavior::unsetPersonalPropertyIfNull($resource->toArray());

        // Log secret access.
        $this->_logSecretAccesses($resource);

        $this->success(__('The operation was successful.'), $resource);
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param array $resource resource
     * @return void
     */
    protected function _logSecretAccesses(array $resource): void
    {
        $Secrets = $this->Resources->getAssociation('Secrets');
        if (!isset($resource['secrets']) || !$Secrets->hasAssociation('SecretAccesses')) {
            return;
        }

        foreach ($resource['secrets'] as $secret) {
            try {
                /** @var \Passbolt\Log\Model\Table\SecretAccessesTable $SecretAccesses */
                $SecretAccesses = $Secrets->getAssociation('SecretAccesses');
                $SecretAccesses->createFromSecretEntity($this->User->getAccessControl(), $secret);
            } catch (Exception $e) {
                throw new InternalErrorException('Could not log secret access entry.', 500, $e);
            }
        }
    }
}
