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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;

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

        // Filter by secrets by userId and the revision by secret revision
        $secretRevisionsBaseQuery = $this->Resources->SecretRevisions
            ->find()
            ->innerJoinWith('Secrets', function (Query $q) {
                return $q->where(['Secrets.user_id' => $this->User->id()]);
            })
            ->where(['SecretRevisions.resource_id' => $resourceId]);

        if ($options['contain']['secret'] ?? false) {
            $secretRevisionsBaseQuery->contain('Secrets', function (Query $q) {
                return $q->where(['Secrets.user_id' => $this->User->id()]);
            });
        }
        if ($options['contain']['creator'] ?? false) {
            $secretRevisionsBaseQuery->contain('Creator');
        }
        if ($options['contain']['creator.profile'] ?? false) {
            $secretRevisionsBaseQuery->contain([
                'Creator' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        // Clone the base query to retrieve the deleted secret revisions if enabled in the settings
        $deletedSecretRevisions = clone $secretRevisionsBaseQuery;

        // This will retrieve the active non-deleted secret revision
        $secretRevisions = $secretRevisionsBaseQuery->find('notDeleted')->toArray();

        $maxNumberOfDeletedRevisionsToServe = SecretRevisionsSettingsGetService::getSettings()->getMaxRevisions() - 1;
        // If past revisions should be served, union them to the active revision
        if ($maxNumberOfDeletedRevisionsToServe > 0) {
            $deletedSecretRevisions
                ->whereNotNull('SecretRevisions.deleted')
                ->limit($maxNumberOfDeletedRevisionsToServe)
                ->orderByDesc('SecretRevisions.deleted');

            $secretRevisions = array_merge($secretRevisions, $deletedSecretRevisions->toArray());
        }

        // Log secret access.
        $this->_logSecretAccesses($secretRevisions, $options);

        $this->success(__('The operation was successful.'), $secretRevisions);
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param array $secretRevisions resources
     * @param array $queryOptions The query options
     * @return void
     */
    protected function _logSecretAccesses(array $secretRevisions, array $queryOptions)
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
