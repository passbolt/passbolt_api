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
 * @since         4.0.0
 */

namespace Passbolt\SsoRecover\Controller\SelfRegistration;

use App\Model\Validation\EmailValidationRule;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Controller\AbstractSsoController;

class HandleErrorController extends AbstractSsoController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['handleError']);
    }

    /**
     * @return void
     */
    public function handleError(): void
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('Ajax/Json request not supported.'));
        }

        $this->User->assertNotLoggedIn();

        $email = $this->request->getQuery('email');
        if (!is_string($email) || !EmailValidationRule::check($email)) {
            throw new BadRequestException(__('The email is required in URL parameters.'));
        }

        $this->set(['message' => __('The user does not exist.')]);

        $this
            ->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('SelfRegistration')
            ->setTemplate('handle_error');
    }
}
