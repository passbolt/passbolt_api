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
 * @since         3.11.0
 */

namespace Passbolt\SsoRecover\Controller;

use App\Controller\AppController;
use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Utility\ExtendedUserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenGetService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\SsoRecover\Form\SsoRecoverStartForm;
use Passbolt\SsoRecover\Service\SsoRecoverStartService;

class RecoverStartController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['start']);
    }

    /**
     * Exchange authentication token for recover URL.
     *
     * @return void
     */
    public function start(): void
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        $this->User->assertNotLoggedIn();

        // Make sure SSO settings are set.
        try {
            $settingsDto = (new SsoSettingsGetService())->getActiveOrFail();
        } catch (RecordNotFoundException $e) {
            throw new BadRequestException(__('No valid SSO settings found.'), null, $e);
        }

        $form = new SsoRecoverStartForm();
        if (!$form->execute($this->getRequest()->getData())) {
            throw new FormValidationException(__('Could not validate the SSO recover request.'), $form);
        }

        // Assert & consume sso auth token
        $ssoAuthService = new SsoAuthenticationTokenGetService();
        try {
            $ssoAuthToken = $ssoAuthService->getOrFail(
                $form->getData('token'),
                SsoState::TYPE_SSO_RECOVER
            );
        } catch (RecordNotFoundException $e) {
            throw new BadRequestException($e->getMessage(), null, $e);
        }

        $uac = new ExtendedUserAccessControl(
            Role::GUEST,
            $ssoAuthToken->user_id,
            null,
            $this->User->ip(),
            $this->User->userAgent()
        );
        $ssoAuthService->assertAndConsume($ssoAuthToken, $uac, $settingsDto->id);

        $url = (new SsoRecoverStartService())->generateAndGetRecoverUrl($ssoAuthToken->user_id);

        $this->success(__('The operation was successful.'), ['url' => $url]);
    }
}
