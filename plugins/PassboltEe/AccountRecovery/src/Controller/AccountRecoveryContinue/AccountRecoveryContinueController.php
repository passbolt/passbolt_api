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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Controller\AccountRecoveryContinue;

use App\Controller\AppController;
use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestGetService;

/**
 * Class AccountRecoveryContinueController
 *
 * @package Passbolt\AccountRecovery\Controller\AccountRecoveryContinue
 * @property \App\Model\Table\UsersTable $Users
 */
class AccountRecoveryContinueController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['get']);

        return parent::beforeFilter($event);
    }

    /**
     * Render a page to continue the account recovery process
     *
     * @param string|null $userId User ID
     * @param string|null $tokenId Token ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the data provided is not valid
     */
    public function get(?string $userId, ?string $tokenId): void
    {
        if (!isset($userId) || !Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is invalid.'));
        }
        if (!isset($tokenId) || !Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The authentication token id is invalid.'));
        }

        if ($this->getRequest()->is('json')) {
            // Do not allow logged in user to recover
            if ($this->User->role() !== Role::GUEST) {
                throw new ForbiddenException(__('Only guests are allowed to proceed with account recovery.'));
            }
            (new AccountRecoveryRequestGetService())->getOrFail($userId, $tokenId);
            $this->success(__('The operation was successful.'));
        } else {
            $this->renderHtml();
        }
    }

    /**
     * @return void
     */
    protected function renderHtml(): void
    {
        $this->viewBuilder()
            ->setVar('title', Configure::read('passbolt.meta.title'))
            ->setLayout('default')
            ->setTemplatePath('AccountRecovery')
            ->setTemplate('account_recovery');
    }
}
