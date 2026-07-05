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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

class PasskeyLoginGetController extends PasskeyController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        // The login ceremony page must be reachable BEFORE any session exists.
        $this->Authentication->allowUnauthenticated(['get']);

        return parent::beforeFilter($event);
    }

    /**
     * Render the top-level login ceremony page served from the passbolt origin. navigator.credentials
     * .get() runs here (top-level) so it binds to the correct relying party id; the raw assertion is
     * bridged back to the browser extension, which owns the passphrase kit and runs GPGAuth.
     *
     * @return void
     */
    public function get(): void
    {
        if ($this->getRequest()->is('json')) {
            throw new NotFoundException(__('Page not found.'));
        }

        $this->set('mode', 'login');
        $this->set('userId', (string)$this->getRequest()->getQuery('user_id', ''));
        $this->set('csrfToken', $this->getRequest()->getAttribute('csrfToken'));
        $this->viewBuilder()
            ->setLayout('passkey_ceremony')
            ->setTemplatePath('Passkey')
            ->setTemplate('ceremony');
    }
}
