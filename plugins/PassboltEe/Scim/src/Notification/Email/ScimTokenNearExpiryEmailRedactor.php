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
namespace Passbolt\Scim\Notification\Email;

use Passbolt\Scim\Authenticator\ScimTokenAuthenticator;
use Passbolt\Scim\Service\Cache\AbstractScimCacheService;
use Passbolt\Scim\Service\Cache\ScimTokenNearExpiryNotificationCacheService;

class ScimTokenNearExpiryEmailRedactor extends AbstractScimTokenExpiryEmailRedactor
{
    public const TEMPLATE = 'Passbolt/Scim.AD/scim_token_near_expiry';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [ScimTokenAuthenticator::EVENT_TOKEN_NEAR_EXPIRY];
    }

    /**
     * @inheritDoc
     */
    protected function getCacheService(): AbstractScimCacheService
    {
        return new ScimTokenNearExpiryNotificationCacheService();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplate(): string
    {
        return self::TEMPLATE;
    }

    /**
     * @inheritDoc
     */
    protected function getSubject(): string
    {
        return __('The SCIM secret token expires soon');
    }
}
