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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Service\SsoKeys;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class SsoKeysDeleteService
{
    /**
     * Delete a Sso key
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $id uuid
     * @return void
     */
    public function delete(UserAccessControl $uac, string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO key id should be a uuid.'));
        }

        $SsoKeys = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoKeys');
        try {
            $entity = $SsoKeys->find()->where(['id' => $id, 'user_id' => $uac->getId()])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The SSO key does not exist.'));
        }

        if (!$SsoKeys->delete($entity)) {
            throw new InternalErrorException(__('The SSO key could not be deleted.'));
        }
    }
}
