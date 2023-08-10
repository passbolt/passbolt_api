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

namespace Passbolt\SsoRecover\Controller\Google;

use App\Error\Exception\CustomValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenGetService;

class GoogleRecoverSuccessController extends AbstractSsoController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['ssoRecoverSuccess']);
    }

    /**
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function ssoRecoverSuccess(): void
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('Ajax/Json request not supported.'));
        }

        $this->User->assertNotLoggedIn();
        $token = $this->getTokenFromUrlQuery();

        try {
            (new SsoAuthenticationTokenGetService())->getActiveNotExpiredOrFail($token, SsoState::TYPE_SSO_RECOVER);
        } catch (RecordNotFoundException $e) {
            throw new BadRequestException(
                __('The authentication token does not exist or has been deleted.'),
                null,
                $e
            );
        } catch (CustomValidationException $e) {
            throw new BadRequestException(
                __('The authentication token has been expired.'),
                null,
                $e
            );
        }

        $this->viewBuilder()
            ->setTheme('Passbolt/Sso')
            ->setLayout('default')
            ->setTemplatePath('azure')
            ->setTemplate('stage3');
    }
}
