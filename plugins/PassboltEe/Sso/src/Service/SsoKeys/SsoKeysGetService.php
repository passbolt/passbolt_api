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
namespace Passbolt\Sso\Service\SsoKeys;

use App\Utility\ExtendedUserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\Sso\Model\Entity\SsoKey;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenGetService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;

class SsoKeysGetService
{
    /**
     * Build entity and perform basic check.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac extended user access control
     * @param string $token SsoAuthenticationToken.token
     * @param string $keyId uuid
     * @return \Passbolt\Sso\Model\Entity\SsoKey
     */
    public function get(ExtendedUserAccessControl $uac, string $token, string $keyId): SsoKey
    {
        try {
            $ssoSettingEntity = (new SsoSettingsGetService())->getActiveOrFail();
            // Token must be provided and matching the settings, user id, ip, user agent, etc.
            $ssoAuthTokenGetService = new SsoAuthenticationTokenGetService();
            $ssoAuthToken = $ssoAuthTokenGetService->getOrFail(
                $token,
                SsoState::TYPE_SSO_GET_KEY
            );
            $ssoAuthTokenGetService->assertAndConsume($ssoAuthToken, $uac, $ssoSettingEntity->id);
        } catch (RecordNotFoundException $exception) {
            throw new BadRequestException($exception->getMessage(), 400, $exception);
        }

        try {
            $SsoKeys = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoKeys');
            /** @var \Passbolt\Sso\Model\Entity\SsoKey $key entity */
            $key = $SsoKeys->find()->where(['id' => $keyId, 'user_id' => $uac->getId()])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new RecordNotFoundException(__('The SSO key does not exist.'), 404, $exception);
        }

        return $key;
    }
}
