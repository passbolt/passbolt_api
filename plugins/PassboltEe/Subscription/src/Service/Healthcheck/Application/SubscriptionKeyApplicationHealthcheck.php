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
 * @since         4.10.0
 */

namespace Passbolt\Subscription\Service\Healthcheck\Application;

use App\Model\Entity\Role;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyValidateService;

class SubscriptionKeyApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Threshold for user limit warning in percent.
     * E.g. 10% (0.1): 9/10 would be considered warnable.
     *
     * @var float
     */
    protected const THRESHOLD_USER_LIMIT = 0.1;

    /**
     * Threshold for expiry warning in days.
     *
     * @var string
     */
    protected const THRESHOLD_EXPIRY = '+30 days';

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * @var string
     */
    protected string $errorLevel = HealthcheckServiceCollector::LEVEL_ERROR;

    /**
     * @var array<string, mixed>
     */
    protected array $result = [];

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->result = $this->checkSubscription();
        $errorLevel = $this->validate($this->result);
        $this->status = $errorLevel === null;
        if ($errorLevel) {
            $this->errorLevel = $errorLevel;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function isPassed(): bool
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        return $this->errorLevel;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Subscription is valid and up to date.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        if (!$this->result) {
            return __('Subscription cannot be found or is invalid/expired.');
        }

        if ($this->result['error']) {
            return __('Subscription invalid/expired ({0}).', $this->result['error']);
        }

        /** @var \Cake\I18n\FrozenDate $expiry */
        $expiry = $this->result['expiry'];

        if ($expiry->isPast()) {
            return __('Subscription expired ({0}).', $expiry->toFormattedDateString());
        }

        if ($this->result['currentUsers'] > $this->result['allowedUsers']) {
            return __(
                'Subscription user count has been exceeded ({0}/{1}).',
                $this->result['currentUsers'],
                $this->result['allowedUsers']
            );
        }

        if ($expiry->isWithinNext(static::THRESHOLD_EXPIRY)) {
            return __('Subscription will expire in {0} days.', $expiry->diffInDays());
        }

        if ($this->limitNearlyReached($this->result['currentUsers'], $this->result['allowedUsers'])) {
            return __(
                'Subscription soon exceeds user count ({0}/{1}).',
                $this->result['currentUsers'],
                $this->result['allowedUsers']
            );
        }

        return __('Subscription invalid or expired.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'subscriptionKeyStatus';
    }

    /**
     * @return array<string, mixed>
     */
    protected function checkSubscription(): array
    {
        $subscriptionKeyGetService = new SubscriptionKeyGetService();
        try {
            $subscriptionKeyDto = $subscriptionKeyGetService->get(new UserAccessControl(Role::ADMIN));
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

        try {
            $subscriptionKeyValidateService = new SubscriptionKeyValidateService();
            $subscriptionKeyValidateService->validate($subscriptionKeyDto->data);
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

        return [
            'error' => null,
            'allowedUsers' => $subscriptionKeyDto->users,
            'currentUsers' => $this->currentUsers(),
            'expiry' => $subscriptionKeyDto->expiry,
        ];
    }

    /**
     * @param array<string, mixed> $result Result
     * @return string|null Error level
     */
    protected function validate(array $result): ?string
    {
        if (!$result) {
            return HealthcheckServiceCollector::LEVEL_ERROR;
        }

        if ($result['error']) {
            return HealthcheckServiceCollector::LEVEL_ERROR;
        }

        /** @var \Cake\I18n\FrozenDate $expiry */
        $expiry = $result['expiry'];
        if ($expiry->isPast()) {
            return HealthcheckServiceCollector::LEVEL_ERROR;
        }

        if ($result['currentUsers'] > $result['allowedUsers']) {
            return HealthcheckServiceCollector::LEVEL_ERROR;
        }

        if ($expiry->isWithinNext(static::THRESHOLD_EXPIRY)) {
            return HealthcheckServiceCollector::LEVEL_WARNING;
        }

        if ($this->limitNearlyReached($result['currentUsers'], $result['allowedUsers'])) {
            return HealthcheckServiceCollector::LEVEL_WARNING;
        }

        return null;
    }

    /**
     * @param int $currentUsers Current
     * @param int $allowedUsers Allowed
     * @return bool
     */
    protected function limitNearlyReached(int $currentUsers, int $allowedUsers): bool
    {
        return $currentUsers >= $allowedUsers - (int)ceil($allowedUsers * static::THRESHOLD_USER_LIMIT);
    }

    /**
     * @return int
     */
    protected function currentUsers(): int
    {
        return TableRegistry::getTableLocator()->get('Users')
            ->find()
            ->where(['Users.deleted' => false])
            ->where(['Users.active' => true])
            ->all()
            ->count();
    }
}
