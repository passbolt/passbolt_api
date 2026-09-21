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
 * @since         5.13.0
 */
namespace Passbolt\JwtAuthentication\Event;

use App\Model\Entity\AuthenticationToken;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Service\EditionDowngradeService;
use Passbolt\Edition\Service\EditionUpgradeService;
use Throwable;

/**
 * Invalidates all active JWT refresh token after edition transition (CE → PRO or PRO → CE).
 */
class JwtAuthenticationLogoutAllUsersOnEditionChangeListener implements EventListenerInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            EditionDowngradeService::EVENT_EDITION_DOWNGRADED => 'invalidateRefreshTokens',
            EditionUpgradeService::EVENT_NAME => 'invalidateRefreshTokens',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Edition.downgraded or Edition.upgraded event.
     * @return void
     */
    public function invalidateRefreshTokens(EventInterface $event): void
    {
        try {
            $this->fetchTable('AuthenticationTokens')->updateAll(
                ['active' => false],
                [
                    'type' => AuthenticationToken::TYPE_REFRESH_TOKEN,
                    'active' => true,
                ],
            );
        } catch (Throwable $e) {
            // Edition change already committed: swallow to avoid a misleading 500.
            $msg = 'Failed to invalidate refresh tokens after edition change.';
            if (Configure::read('debug')) {
                $msg .= ' ' . $e->getMessage();
            }
            Log::error($msg);
        }
    }
}
