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
namespace App\Controller\Auth;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use Authentication\Authenticator\Result;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;

class AuthLoginController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated([
            'loginGet',
            'loginPost',
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * User login get action
     * Display the login page
     *
     * @return void
     */
    public function loginGet()
    {
        if ($this->request->is('json')) {
            throw new NotFoundException(__('Page not found.'));
        }

        // Do not allow logged in user to login again
        if ($this->User->role() !== Role::GUEST) {
            $this->redirect('/'); // user is already logged in
        }

        $this->set('title', Configure::read('passbolt.meta.description'));

        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('Auth')
            ->setTemplate('triage');
    }

    /**
     * User login post action
     *
     * @return void
     */
    public function loginPost()
    {
        $this->assertJson();

        // Custom X-GpgAuth-* http headers are stored in $result->getErrors
        // They are translated into actual http headers as part of GpgAuthHeadersMiddleware::process
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $data = $result->getData();
            $user = $data['user'];
            $uac = new UserAccessControl($user['role']['name'], $user['id']);
            UserAction::getInstance()->setUserAccessControl($uac);
            $this->success(__('You are successfully logged in.'), $user);
        } else {
            $errors = $result->getErrors();
            $message = $errors['X-GPGAuth-Debug'] ?? 'The authentication failed.';

            switch ($result->getStatus()) {
                case Result::FAILURE_CREDENTIALS_MISSING:
                    // We return 200 because it's partial success and BExt relies on this status code
                    // Changing this would mean breaking compatibility. Be careful!
                    $this->error($message, null, 200);
                    break;
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    $this->error($message);
                    break;
                case Result::FAILURE_CREDENTIALS_INVALID:
                    $this->error($message);
                    break;
                case Result::FAILURE_OTHER:
                    throw new InternalErrorException($message);
            }
        }
    }
}
