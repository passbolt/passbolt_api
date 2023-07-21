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

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class UpdateMfaTokenSessionIdService
 */
class UpdateMfaTokenSessionIdService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * UpdateMfaTokenSessionIdService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * @param string $mfaToken MFA Token to update
     * @param string $sessionId Session ID
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no matching MFA token.
     * @throws \Cake\ORM\Exception\PersistenceFailedException When the entity couldn't be saved
     */
    public function updateSessionId(string $mfaToken, string $sessionId): AuthenticationToken
    {
        /** @var \App\Model\Entity\AuthenticationToken|null $mfaToken */
        $mfaToken = $this->AuthenticationTokens->find()
            ->where([
                'active' => true,
                'token' => $mfaToken,
                'type' => AuthenticationToken::TYPE_MFA,
            ])
            ->first();

        if ($mfaToken === null) {
            throw new RecordNotFoundException(__('The MFA token provided does not exist or is inactive.'));
        }

        $mfaToken->hashAndSetSessionId($sessionId);

        return $this->AuthenticationTokens->saveOrFail($mfaToken);
    }
}
