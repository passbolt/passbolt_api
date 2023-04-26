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
namespace Passbolt\JwtAuthentication\Service\VerifyToken;

use App\Model\Entity\AuthenticationToken;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class VerifyTokenCreateService
 */
class VerifyTokenCreateService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * VerifyTokenCreateService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * @param string $verifyToken Verify token
     * @param string $userId user UUID
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \App\Error\Exception\ValidationException if the token could not be generated.
     */
    public function createToken(string $verifyToken, string $userId): AuthenticationToken
    {
        $this->cleanupAllExpiredTokens();

        return $this->AuthenticationTokens->generate(
            $userId,
            AuthenticationToken::TYPE_VERIFY_TOKEN,
            $verifyToken
        );
    }

    /**
     * @return void
     */
    protected function cleanupAllExpiredTokens(): void
    {
        $minTokenCreationTime = FrozenTime::now()
            ->modify('-' . Configure::read(VerifyTokenValidationService::VERIFY_TOKEN_EXPIRY_CONFIG_KEY));

        $this->AuthenticationTokens->deleteAll([
            $this->AuthenticationTokens->aliasField('type') => AuthenticationToken::TYPE_VERIFY_TOKEN,
            $this->AuthenticationTokens->aliasField('created') . ' <' => $minTokenCreationTime,
        ]);
    }
}
