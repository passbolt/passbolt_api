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
namespace App\Controller\Setup;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Service\Setup\RecoverAbortService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;

class RecoverAbortController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['abort']);

        return parent::beforeFilter($event);
    }

    /**
     * Recovery abort
     *
     * @param string $userId uuid of the user
     * @return void
     */
    public function abort(string $userId)
    {
        $this->assertJson();

        // Do not allow logged in user to abort
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guests are allowed to abort recovery process.'));
        }

        // Make sure token is provided
        $token = $this->getRequest()->getData('authentication_token.token') ?? null;
        if (!isset($token) || !is_string($token)) {
            throw new BadRequestException(__('An authentication token must be provided.'));
        }

        (new RecoverAbortService())->abort($userId, $token);

        $this->success(__('The recovery process was aborted.'));
    }
}
