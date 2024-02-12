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
 * @since         2.13.0
 */
namespace Passbolt\Reports\Service;

use InvalidArgumentException;

/**
 * Create report service instance. Use the ReportServicePool.
 *
 * @package Passbolt\Reports\Factory
 */
class ReportViewService
{
    /**
     * @var \Passbolt\Reports\Service\ReportPool
     */
    private $reportPool;

    /**
     * @param \Passbolt\Reports\Service\ReportPool $reportPool An instance of ReportPool
     */
    public function __construct(?ReportPool $reportPool = null)
    {
        $this->reportPool = $reportPool ?? ReportPool::getInstance();
    }

    /**
     * Build a object implementing AbstractReport for given slug
     *
     * @param string $reportSlug Slug of the report
     * @param array|null $parameters The report parameters
     * @throws \ReflectionException
     * @return \Passbolt\Reports\Utility\AbstractReport
     */
    public function getReport(string $reportSlug, ?array $parameters = [])
    {
        $reports = $this->reportPool->getReports();
        /** @var class-string $reportClass */
        $reportClass = $reports[$reportSlug] ?? false;

        if (!$reportClass) {
            throw new InvalidArgumentException();
        }

        $reflectionClass = new \ReflectionClass($reportClass);

        /** @var \Passbolt\Reports\Utility\AbstractReport $report */
        $report = $reflectionClass->newInstance(...$parameters);

        return $report;
    }
}
