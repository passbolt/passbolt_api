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
namespace Passbolt\Reports\Utility;

interface CombinedReportInterface
{
    /**
     * Add a report to the combined report list
     *
     * @param \Passbolt\Reports\Utility\ReportInterface $report report
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function addReport(ReportInterface $report): ReportInterface;

    /**
     * Get the sub reports list
     *
     * @return \Passbolt\Reports\Utility\ReportInterface[]
     */
    public function getReports(): array;
}
