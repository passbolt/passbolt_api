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
 * @since         5.16.0
 */
namespace App\Service\Healthcheck;

use Cake\Collection\Collection;

class HealthcheckApiSerializerService
{
    public const STATUS_PASS = 'pass';
    public const STATUS_ERROR = 'error';
    public const STATUS_WARNING = 'warning';
    public const STATUS_INFO = 'info';

    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceCollector
     */
    private HealthcheckServiceCollector $collector;

    /**
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $collector Used to resolve domain display titles.
     */
    public function __construct(HealthcheckServiceCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Serializes executed healthcheck results, grouped by domain, in input order.
     *
     * @return array
     */
    public function serialize(): array
    {
        $healthcheckServices = new Collection($this->collector->getServices());
        $grouped = $healthcheckServices
            ->map(function (HealthcheckServiceInterface $healthcheckService) {
                return $healthcheckService->check();
            })
            ->reject(function (HealthcheckServiceInterface $healthcheck) {
                return $healthcheck instanceof SkipHealthcheckInterface && $healthcheck->isSkipped();
            })
            ->groupBy(function (HealthcheckServiceInterface $healthcheck) {
                return $healthcheck->domain();
            });

        $body = [];
        foreach ($grouped as $domain => $domainHealthcheckServices) {
            $title = $this->collector->getTitleFromDomain($domain);

            $body[$title] = array_map(function (HealthcheckServiceInterface $healthcheck) {
                return [
                    'status' => $this->status($healthcheck),
                    'message' => $this->message($healthcheck),
                    'help' => $this->help($healthcheck),
                ];
            }, $domainHealthcheckServices);
        }

        return $body;
    }

    /**
     * if level() is NOTICE then it is an INFO
     * if level() is WARNING or ERROR only matters if it fails
     * if fails and level() is WARNING then status is a WARNING
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return string
     */
    private function status(HealthcheckServiceInterface $result): string
    {
        if ($result->level() === HealthcheckServiceCollector::LEVEL_NOTICE) {
            return self::STATUS_INFO;
        }

        if ($result->isPassed()) {
            return self::STATUS_PASS;
        }

        if ($result->level() === HealthcheckServiceCollector::LEVEL_WARNING) {
            return self::STATUS_WARNING;
        }

        return self::STATUS_ERROR;
    }

    /**
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return string
     */
    private function message(HealthcheckServiceInterface $result): string
    {
        return $result->isPassed()
            ? $result->getSuccessMessage()
            : $result->getFailureMessage();
    }

    /**
     * Help is only emitted if failure.
     * Normalised to an array because implementations return string, array or null.
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return array
     */
    private function help(HealthcheckServiceInterface $result): array
    {
        if ($result->isPassed()) {
            return [];
        }

        $help = $result->getHelpMessage();

        if ($help === null) {
            return [];
        }

        return is_array($help) ? array_values($help) : [$help];
    }
}
