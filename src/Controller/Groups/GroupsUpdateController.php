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

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Service\Groups\GroupsUpdateDryRunService;
use App\Service\Groups\GroupsUpdateService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * GroupsUpdateController Class
 */
class GroupsUpdateController extends AppController
{
    /**
     * @var \App\Model\Table\GroupsTable
     */
    protected $Groups;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    protected $GroupsUsers;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    protected $Secrets;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Groups = $this->fetchTable('Groups');
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        $this->Resources = $this->fetchTable('Resources');
        $this->Secrets = $this->fetchTable('Secrets');
    }

    /**
     * Group Update action.
     *
     * @param string $id The group identifier.
     * @param \App\Service\Groups\GroupsUpdateService $groupsUpdateService Service to update the group associations.
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not an admin
     * @throws \App\Error\Exception\ValidationException If an error occurred when patching or saving the group
     * @throws \Exception If an unexpected error occurred
     */
    public function update(string $id, GroupsUpdateService $groupsUpdateService)
    {
        $this->assertJson();

        $uac = $this->User->getAccessControl();
        $this->assertRequestParameter($uac, $id);

        $data = $this->_formatRequestData();
        $name = Hash::get($data, 'name');
        $metaData = [];
        if ($name) {
            $metaData['name'] = $name;
        }
        $changes = Hash::get($data, 'groups_users', []);
        $secrets = Hash::get($data, 'secrets', []);

        $groupsUpdateService->update($uac, $id, $metaData, $changes, $secrets);

        // The v1 expect the updated group to be returned.
        $viewOptions = [
            'contain' => ['groups_users' => 1, 'groups_users.user.profile' => 1],
        ];
        $group = $this->Groups->findView($id, $viewOptions)->first();
        $this->success(__('The operation was successful.'), $group);
    }

    /**
     * Assert the request parameter.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param string $id group uuid
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the operator is not a group manager or an admin
     * @throws \Cake\Http\Exception\BadRequestException if the group uuid id invalid
     */
    protected function assertRequestParameter(UserAccessControl $uac, string $id)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group id is not valid.'));
        }

        $exists = $this->Groups->exists(['id' => $id]);
        if (!$exists) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        // If the user is not manager of the group nor admin
        $isGroupManager = $this->GroupsUsers->isManager($uac->getId(), $id);
        $isAdmin = $uac->isAdmin();
        if (!$isGroupManager && !$isAdmin) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();

        // Group given in V1 format.
        if (isset($data['Group'])) {
            $data = array_merge_recursive($data, Hash::extract($data, 'Group'));
            unset($data['Group']);
        }
        // GroupsUsers given in V1 format.
        if (!empty($data['GroupUsers'])) {
            $data['groups_users'] = Hash::extract($data['GroupUsers'], '{n}.GroupUser');
            unset($data['GroupUsers']);
        }
        // Secrets given in V1 format.
        if (isset($data['Secrets'])) {
            $data['secrets'] = Hash::extract($data['Secrets'], '{n}.Secret');
            unset($data['Secrets']);
        }

        return $data;
    }

    /**
     * Group Update Dry Run action
     *
     * @param string $id The identifier of the group to update.
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not an admin
     * @throws \App\Error\Exception\ValidationException If an error occurred when patching or saving the group
     * @throws \Exception If something unexpected occurred
     */
    public function dryRun(string $id)
    {
        $this->assertJson();

        $uac = $this->User->getAccessControl();
        $this->assertRequestParameter($uac, $id);

        $data = $this->_formatRequestData();
        $changes = Hash::get($data, 'groups_users', []);

        $groupsUpdateDryRunService = new GroupsUpdateDryRunService();
        $dryRunResult = $groupsUpdateDryRunService->dryRun($uac, $id, $changes);
        $secretsNeeded = Hash::get($dryRunResult, 'secretsNeeded');
        $secrets = Hash::get($dryRunResult, 'secrets');

        // Format the result and
        $result = $this->_formatDryRunResult($secretsNeeded, $secrets);
        $this->success(__('The operation was successful.'), $result);
    }

    /**
     * Format the result.
     *
     * This entry point is used by the plugin app, and due to the V1 legacy the output body must be
     * formatted as following:
     *
     * [
     *   'SecretsNeeded' => [
     *     [
     *       'Secret' => [
     *         'resource_id' => uuid,
     *         'user_id' => uuid
     *       ],
     *       ...
     *     ]
     *   ],
     *   'Secrets' => [
     *     [
     *       'Secret' => [
     *         'id' => uuid,
     *         'resource_id' => uuid,
     *         'user_id' => uuid,
     *         'data' => gpg_armored_text
     *       ],
     *     ],
     *     ...
     * ]
     *
     * @param array $secretsToRequest The list of secrets to request for encryption to the client.
     * @param array $userSecrets The current user secrets that will be used to encrypt the new secrets.
     * @return array
     */
    private function _formatDryRunResult(array $secretsToRequest, array $userSecrets)
    {
        $result = [
            'dry-run' => [
                'SecretsNeeded' => [],
                'Secrets' => [],
            ],
        ];

        // Format the content.
        foreach ($secretsToRequest as $secret) {
            $result['dry-run']['SecretsNeeded'][] = ['Secret' => $secret];
        }
        foreach ($userSecrets as $secret) {
            $result['dry-run']['Secrets'][] = ['Secret' => [$secret]];
        }

        return $result;
    }
}
