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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Controller;

use App\Controller\AppController;
use App\Model\Table\AvatarsTable;
use App\Model\Table\ResourcesTable;
use Cake\Collection\CollectionInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;

class SecretRevisionsResourceGetController extends AppController
{
    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected ResourcesTable $Resources;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * Get the secret revisions of a provided resource
     *
     * @return void
     */
    public function get(string $resourceId): void
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        // Retrieve and sanity the query options.
        $whitelist = ['contain' => [
            'creator', 'creator.profile', 'secret',
        ]];
        $options = $this->QueryString->get($whitelist);

        // Retrieve the resource.
        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->findView($this->User->id(), $resourceId)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        $secretRevisions = $this->Resources->SecretRevisions
            ->find()
            ->innerJoinWith('Secrets', function (Query $q) {
                return $q->where(['Secrets.user_id' => $this->User->id()]);
            })
            ->where(['SecretRevisions.resource_id' => $resourceId])
            ->orderByDesc('SecretRevisions.created');

        if ($options['contain']['secret'] ?? false) {
            $secretRevisions->contain('Secrets', function (Query $q) {
                return $q->where(['Secrets.user_id' => $this->User->id()]);
            });
        }
        if ($options['contain']['creator'] ?? false) {
            $secretRevisions->contain('Creator');
        }
        if ($options['contain']['creator.profile'] ?? false) {
            $secretRevisions->contain([
                'Creator' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }
        // Log secret access.
        $this->_logSecretAccesses($secretRevisions->all(), $options);

        $this->success(__('The operation was successful.'), $secretRevisions->all());
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param \Cake\Collection\CollectionInterface $secretRevisions resources
     * @param array $queryOptions The query options
     * @return void
     */
    protected function _logSecretAccesses(CollectionInterface $secretRevisions, array $queryOptions)
    {
        $containSecret = (bool)Hash::get($queryOptions, 'contain.secret');
        if (!$containSecret) {
            return;
        }

        if (!$this->Resources->getAssociation('Secrets')->hasAssociation('SecretAccesses')) {
            return;
        }

        foreach ($secretRevisions as $secretRevision) {
            $secrets = Hash::get($secretRevision, 'secrets');
            if (!isset($secrets)) {
                continue;
            }

            foreach ($secrets as $secret) {
                // Do not log the access to deleted secrets
                if ($secret['deleted']) {
                    continue;
                }
                try {
                    $this->Resources->Secrets->SecretAccesses->createFromSecretDetails(
                        $this->User->getAccessControl(),
                        Hash::get($secret, 'resource_id'),
                        Hash::get($secret, 'id'),
                    );
                } catch (Exception $e) {
                    throw new InternalErrorException('Could not log secret access entry.', 500, $e);
                }
            }
        }
    }
}
