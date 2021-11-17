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
 * @since         3.3.1
 */
namespace App\Authenticator;

use App\Authenticator\Identifier\SessionIdentifier;
use Authentication\AuthenticationService;

class SessionAuthenticationService extends AuthenticationService
{
    /**
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        // We load an identifier here in to activate the session identification.
        // Any Identifier here will do.
        // If the user is not logged in, this identifier will always fail.
        // If the user is logged in, this identifier will check that the user
        // with the provided username is active and not soft deleted.
        $this->loadIdentifier(SessionIdentifier::class, [
            'usernameField' => SessionIdentifier::CREDENTIAL_USERNAME,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'activeNotDeletedContainRole',
            ],
        ]);

        // Load the default authenticators. Session should be first.
        $this->loadAuthenticator('Authentication.Session', [
            'identify' => true,
        ]);
        $this->loadAuthenticator('Gpg');
    }
}
