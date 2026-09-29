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
namespace Passbolt\MultiFactorAuthentication\Authenticator;

use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Authenticates the anonymous delegated endpoints from the delegated state present in the request.
 */
class DelegatedStateAuthenticator extends AbstractAuthenticator
{
    public const STATE_PARAM = 'mfa_delegated_state';

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $state = $this->readStateFromRequest($request);
        if ($state === null) {
            return new Result(null, Result::FAILURE_CREDENTIALS_MISSING);
        }

        try {
            $uac = (new MfaWebauthnDelegatedStateService())->resolve($state);
            /** @var \App\Model\Entity\User $user */
            $user = TableRegistry::getTableLocator()
                ->get('Users')
                ->find('activeNotDeletedNotDisabledContainRole')
                ->where(['Users.id' => $uac->getId()])
                ->firstOrFail();
        } catch (BadRequestException | RecordNotFoundException $e) {
            return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
        }

        return new Result(compact('user'), Result::SUCCESS);
    }

    /**
     * Reads the state either from the request body or query param.
     *
     * @param \Cake\Http\ServerRequest $request Server request.
     * @return string|null Null when the request carries no state at all.
     */
    private function readStateFromRequest(ServerRequest $request): ?string
    {
        $body = $request->getData(self::STATE_PARAM);
        if (is_string($body) && !empty($body)) {
            return $body;
        }

        $query = $request->getQuery(self::STATE_PARAM);
        if (is_string($query) && !empty($query)) {
            return $query;
        }

        return null;
    }
}
