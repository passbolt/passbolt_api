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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Authenticator\DelegatedStateAuthenticator;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;

class WebauthnDelegatedGetController extends AppController
{
    /**
     * @var string
     */
    public const REDIRECT_URI = 'passbolt://mfa/webauthn/callback';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['get']);

        parent::beforeFilter($event);
    }

    /**
     * Serves the page used to run the passkey ceremony in the external browser.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService $delegatedStateService Delegated state service.
     * @return void
     */
    public function get(MfaWebauthnDelegatedStateService $delegatedStateService): void
    {
        $state = $this->getRequest()->getQuery(DelegatedStateAuthenticator::STATE_PARAM);
        if (!is_string($state) || !Validation::uuid($state)) {
            $this->renderTemplate('delegatedExpired', [
                'redirectUri' => self::REDIRECT_URI,
                'errorCode' => 'state_invalid',
            ]);

            return;
        }

        try {
            $delegatedStateService->resolve($state);
        } catch (BadRequestException $e) {
            $this->renderTemplate('delegatedExpired', [
                'redirectUri' => self::REDIRECT_URI,
                'errorCode' => 'state_invalid',
            ]);

            return;
        }

        $this->renderTemplate('delegatedVerify', [
            'state' => $state,
            'redirectUri' => self::REDIRECT_URI,
        ], 'default');
    }

    /**
     * @param string $template Template name.
     * @param array<string, string> $viewVariables Variables the template prints.
     * @param string $layout Layout name.
     * @return void
     */
    private function renderTemplate(string $template, array $viewVariables, string $layout = 'mfa_verify'): void
    {
        $this->set($viewVariables);
        $this->viewBuilder()
            ->setLayout($layout)
            ->setTemplatePath('Webauthn')
            ->setTemplate($template);
    }
}
