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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Controller;

use App\Controller\AppController;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use Authentication\Authenticator\Result;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;

class JwtLoginController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated([
            'loginPost',
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * User login post action
     *
     * @return void
     */
    public function loginPost()
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $challenge = $result->getData()['challenge'];
            $user = $result->getData()['user'];
            $uac = new UserAccessControl($user['role']['name'], $user['id']);
            UserAction::getInstance()->setUserAccessControl($uac);
            $this->success(__('The authentication was a success.'), compact('challenge'));
        } else {
            $message = __('The authentication failed.') . ' ';
            switch ($result->getStatus()) {
                case Result::FAILURE_CREDENTIALS_MISSING:
                    $message .= __('The credentials are missing.');
                    throw new BadRequestException($message);
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    $message = __('The user does not exist or is not active or has been deleted.');
                    throw new NotFoundException($message);
                case Result::FAILURE_CREDENTIALS_INVALID:
                    $message = __('The credentials are invalid.');
                    throw new BadRequestException($message);
                default:
                case Result::FAILURE_OTHER:
                    $message = __('An internal error occurred.');
                    throw new InternalErrorException($message);
            }
        }
    }
}
