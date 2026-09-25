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
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedCeremonyService;
use Psr\Http\Message\ServerRequestInterface;

class DelegatedCeremonyTokenAuthenticator extends AbstractAuthenticator
{
    public const TOKEN_PARAM = 'ceremony_token';

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $token = $this->readCeremonyTokenInRequest($request);
        if ($token === null) {
            return new Result(null, Result::FAILURE_CREDENTIALS_MISSING);
        }

        try {
            $uac = (new MfaWebauthnDelegatedCeremonyService())->resolve($token);
            /** @var \App\Model\Entity\User $user */
            $user = TableRegistry::getTableLocator()
                ->get('Users')
                ->find('activeNotDeletedNotDisabledContainRole')
                ->where(['Users.id' => $uac->getId()])
                ->firstOrFail();
        } catch (NotFoundException | RecordNotFoundException $e) {
            return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
        }

        return new Result(compact('user'), Result::SUCCESS);
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server request.
     * @return string|null
     */
    private function readCeremonyTokenInRequest(ServerRequest $request): ?string
    {
        $body = $request->getData(self::TOKEN_PARAM);
        if (is_string($body) && $body !== '') {
            return $body;
        }

        $query = $request->getQuery(self::TOKEN_PARAM);
        if (is_string($query) && $query !== '') {
            return $query;
        }

        return null;
    }
}
