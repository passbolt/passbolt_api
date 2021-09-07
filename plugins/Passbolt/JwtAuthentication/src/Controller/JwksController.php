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
use Cake\Event\EventInterface;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;

class JwksController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['rsa', 'jwks']);
    }

    /**
     * Serve the JWT public key
     *
     * @return void
     */
    public function rsa(): void
    {
        $keydata['keydata'] = (new JwksGetService())->getRawPublicKey();
        $this->success(__('The operation was successful.'), $keydata);
    }

    /**
     * Serve the JWT public key
     *
     * @return void
     */
    public function jwks(): void
    {
        $keys['keys'][] = (new JwksGetService())->getPublicKey();

        // Do not use regular envelope as this is a normalized endpoint
        $this->set(compact('keys'));
        $this->viewBuilder()->setOption('serialize', 'keys');
        $this->setViewBuilderOptions();
    }
}
