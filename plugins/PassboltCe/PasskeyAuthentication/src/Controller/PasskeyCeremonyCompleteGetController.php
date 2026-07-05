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

/**
 * The url the ceremony page navigates to once it has deposited its credential. The browser extension
 * watches the popup for this url (carrying the challenge token) to know the ceremony completed and to
 * collect the credential. The page itself only renders a short "you can close this window" notice.
 */
class PasskeyCeremonyCompleteGetController extends PasskeyController
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
     * @return void
     */
    public function get(): void
    {
        if ($this->getRequest()->is('json')) {
            throw new NotFoundException(__('Page not found.'));
        }

        $this->set('csrfToken', $this->getRequest()->getAttribute('csrfToken'));
        $this->viewBuilder()
            ->setLayout('passkey_ceremony')
            ->setTemplatePath('Passkey')
            ->setTemplate('complete');
    }
}
