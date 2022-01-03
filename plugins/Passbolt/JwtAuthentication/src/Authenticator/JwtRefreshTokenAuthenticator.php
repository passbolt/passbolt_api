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
 * @since         4.0.0
 */
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Passbolt\JwtAuthentication\Error\Exception\AbstractJwtAttackException;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAuthenticationService;
use Psr\Http\Message\ServerRequestInterface;

class JwtRefreshTokenAuthenticator extends AbstractAuthenticator
{
    use ContainerAwareMiddlewareTrait;

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        try {
            /** @var \Cake\Http\ServerRequest $request */
            $refreshToken = $this->readRefreshTokenInRequest($request);
            $user = $this->findUser($refreshToken->user_id);
        } catch (RecordNotFoundException $exception) {
            throw new RefreshTokenNotFoundException();
        } catch (AbstractJwtAttackException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage());
        }

        $this->extendSessionIdentificationServiceInterfaceInDIC($request, $refreshToken);

        return new Result(compact('user'), Result::SUCCESS);
    }

    /**
     * @param string $userId uuid
     * @return \App\Model\Entity\User
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if the user cannot be found, is deleted, is not active
     */
    protected function findUser(string $userId): User
    {
        /** @var \App\Model\Entity\User $user */
        $user = TableRegistry::getTableLocator()
            ->get('Users')
            ->find('activeNotDeletedContainRole')
            ->where(['Users.id' => $userId])
            ->firstOrFail();

        return $user;
    }

    /**
     * Reads the refresh token in the payload or in the cookies
     *
     * @param \Cake\Http\ServerRequest $request Server request
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException if the user token cannot be found in the cookie
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    protected function readRefreshTokenInRequest(ServerRequest $request): AuthenticationToken
    {
        $service = new RefreshTokenAuthenticationService();
        if ($this->isValidPayloadProvided($request, $service)) {
            $refreshToken = $service->getActiveRefreshToken(
                $request->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY),
                $request->getData('user_id')
            );
        } else {
            /** @var \App\Model\Entity\AuthenticationToken $refreshToken */
            $refreshToken = $service->queryRefreshToken(
                $request->getCookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE)
            )->firstOrFail();
        }

        $service->throwSecurityExceptionsOnInvalidRefreshToken($refreshToken);

        return $refreshToken;
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server request
     * @param \App\Model\Entity\AuthenticationToken $refreshToken Valid user authenticating refresh token
     * @return void
     */
    protected function extendSessionIdentificationServiceInterfaceInDIC(
        ServerRequest $request,
        AuthenticationToken $refreshToken
    ): void {
        $this
            ->getContainer($request)
            ->extend(SessionIdentificationServiceInterface::class)
            ->setConcrete(RefreshTokenSessionIdentificationService::class)
            ->addArgument($refreshToken);
    }

    /**
     * Is the user ID and the refresh token in the payload.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @param \Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAuthenticationService $service Helping service
     * @return bool
     * @throws \InvalidArgumentException if the refresh token is not valid
     * @throws \InvalidArgumentException if the user id is not valid
     */
    protected function isValidPayloadProvided(ServerRequest $request, RefreshTokenAuthenticationService $service): bool
    {
        $token = $request->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY);
        $userId = $request->getData('user_id');
        $isPayloadProvided = is_string($token) || is_string($userId);
        if ($isPayloadProvided) {
            $service->validateRefreshToken($token);
            $service->validateUserId($userId);
        }

        return $isPayloadProvided;
    }
}
