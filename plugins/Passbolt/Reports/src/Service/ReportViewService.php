<?php
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
namespace Passbolt\Reports\Utility;

use InvalidArgumentException;

/**
 * Create report service instance. Use the ReportServicePool.
 *
 * @package Passbolt\Reports\Factory
 */
class ReportViewService
{
    /**
     * @var ReportPool
     */
    private $reportPool;

    /**
     * @param ReportPool $reportPool An instance of ReportPool
     */
    public function __construct(ReportPool $reportPool = null)
    {
        $this->reportPool = $reportPool ?? ReportPool::getInstance();
    }

    /**
     * Build a object implementing ReportInterface for given slug
     *
     * @param string $reportSlug Slug of the report
     * @throws InvalidArgumentException if the slug is not supported
     * @return ReportInterface
     */
    public function getReport(string $reportSlug)
    {
        $reports = $this->reportPool->getReports();
        $report = $reports[$reportSlug] ?? false;

        if (!$report) {
            throw new InvalidArgumentException();
        }

        return $report($this);
    }
}
