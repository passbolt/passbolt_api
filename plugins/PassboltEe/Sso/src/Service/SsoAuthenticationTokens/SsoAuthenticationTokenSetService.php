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
namespace Passbolt\Sso\Service\SsoAuthenticationTokens;

use App\Utility\ExtendedUserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Sso\Model\Entity\SsoAuthenticationToken;

class SsoAuthenticationTokenSetService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable $SsoAuthenticationTokens
     */
    protected $SsoAuthenticationTokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->SsoAuthenticationTokens = $this->fetchTable('Passbolt/Sso.SsoAuthenticationTokens');
    }

    /**
     * @param \App\Utility\ExtendedUserAccessControl $uac UAC object.
     * @param string $type SSO auth type.
     * @param string $settingsId SSO settings ID.
     * @return \Passbolt\Sso\Model\Entity\SsoAuthenticationToken
     * @throws \App\Error\Exception\ValidationException If the user is not valid.
     */
    public function createOrFail(
        ExtendedUserAccessControl $uac,
        string $type,
        string $settingsId
    ): SsoAuthenticationToken {
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $ssoAuthToken */
        $ssoAuthToken = $this->SsoAuthenticationTokens->generate(
            $uac->getId(),
            $type,
            null, // so it will be generated
            [
                'ip' => $uac->getUserIp(),
                'user_agent' => $uac->getUserAgent(),
                'sso_setting_id' => $settingsId,
            ]
        );

        return $ssoAuthToken;
    }
}
