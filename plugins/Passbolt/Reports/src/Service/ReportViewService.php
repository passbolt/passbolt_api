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

use App\Model\Entity\User;
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
    private $reportServicePool;

    /**
     * @param ReportPool $reportServicePool An instance of ReportServicePool
     */
    public function __construct(ReportPool $reportServicePool = null)
    {
        $this->reportServicePool = $reportServicePool ?? new ReportPool();
    }

    /**
     * Return a report service collection
     *
     * @param string $reportSlug Slug of the report
     * @return ReportInterface
     * @throws InvalidArgumentException
     */
    public function getReport(string $reportSlug)
    {
        $reportServices = $this->reportServicePool->getReports($reportSlug);
        $reportServiceCollection = $reportServices[$reportSlug] ?? false;

        if (!$reportServiceCollection) {
            throw new InvalidArgumentException();
        }

        return $reportServiceCollection($this);
    }
}
