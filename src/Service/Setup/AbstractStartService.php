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
 * @since         3.5.0
 */

namespace App\Service\Setup;

use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * AbstractStartService class
 */
abstract class AbstractStartService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * @var \App\Model\Table\UserAgentsTable
     */
    protected $UserAgents;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * AbstractStartService constructor
     *
     * @param \Cake\Http\ServerRequest|null $request Server Request
     */
    public function __construct(?ServerRequest $request = null)
    {
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        /** @phpstan-ignore-next-line */
        $this->UserAgents = $this->fetchTable('UserAgents');
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
        $this->request = $request ?? new ServerRequest();
    }

    /**
     * Assert if the browser is supported. Redirect if the browser is not supported.
     *
     * @return bool
     */
    protected function isBrowserSupported(): bool
    {
        $supportedBrowsers = ['firefox', 'chrome'];
        $browserName = strtolower($this->UserAgents->browserName());
        if (!in_array($browserName, $supportedBrowsers)) {
            return false;
        }

        return true;
    }
}
