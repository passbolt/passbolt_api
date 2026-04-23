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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller\Success;

use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Controller\AbstractSsoController;

class SsoSuccessDryRunController extends AbstractSsoController
{
    /**
     * @return void
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function ssoSuccess(): void
    {
        if ($this->request->is('json')) {
            throw new BadRequestException(__('Ajax/Json request not supported.'));
        }

        $this->User->assertIsAdmin();
        $this->getTokenFromUrlQuery();

        // Not much to do
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('azure')
            ->setTemplate('stage3');
    }
}
