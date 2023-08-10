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
namespace Passbolt\Sso\Controller\Keys;

use App\Model\Entity\Role;
use App\Service\Users\UserGetService;
use App\Utility\ExtendedUserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Service\SsoKeys\SsoKeysGetService;

class SsoKeysGetController extends AbstractSsoController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['get']);

        return parent::beforeFilter($event);
    }

    /**
     * Get a SSO Passphrase Key
     *
     * @param string $keyId uuid
     * @param string $userId uuid
     * @param string $token uuid
     * @throws \Cake\Http\Exception\BadRequestException if user or token id is invalid
     * @throws \Cake\Http\Exception\NotFoundException if the key could not be found for the given user and token
     * @return void
     */
    public function get(string $keyId, string $userId, string $token): void
    {
        $this->User->assertNotLoggedIn();

        try {
            $user = (new UserGetService())->getActiveNotDeletedOrFail($userId);
            $uac = new ExtendedUserAccessControl(
                Role::GUEST,
                $userId,
                $user->username,
                $this->User->ip(),
                $this->User->userAgent()
            );
            $key = (new SsoKeysGetService())->get($uac, $token, $keyId);
        } catch (BadRequestException $exception) {
            // Hide error details to prevent enumerations
            throw new BadRequestException(__('The request is invalid.'), 400, $exception);
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The key could not be found'), 404, $exception);
        }

        $this->success(__('The operation was successful'), $key);
    }
}
