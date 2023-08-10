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

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Sso\Model\Entity\SsoState;

/**
 * Used in 20230206055150_V3110RefactorSsoStates migration.
 */
class DeleteSsoStateAuthenticationTokenService
{
    use LocatorAwareTrait;

    /**
     * Deletes all the records with type "sso_state" from `authentication_tokens` table.
     *
     * @return void
     */
    public function delete(): void
    {
        /** @var \Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable $ssoAuthenticationTokensTable */
        $ssoAuthenticationTokensTable = $this->fetchTable('Passbolt/Sso.SsoAuthenticationTokens');

        $ssoAuthenticationTokensTable->deleteAll(['type' => SsoState::TYPE_SSO_STATE]);
    }
}
