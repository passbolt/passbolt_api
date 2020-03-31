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
use App\Model\Table\Dto\FindIndexOptions;
use JsonSerializable;

interface ReportServiceInterface extends JsonSerializable
{
    /**
     * Generate and return a report object from the provided data.
     * Must throw InvalidArgumentException if the parameters provided in data are not supported.
     *
     * @param User             $creator User who creates the report
     * @param FindIndexOptions $options Options to use to generate the report
     * @return Report
     */
    public function createReport(User $creator, FindIndexOptions $options = null);

    /**
     * Return the supported report type by the report generator. A report generator can only support one type of report.
     * (However, multiple report generators could support the same report type).
     * @return string
     */
    public function getReportSlug();

    /**
     * Return a list of filters, order, contain available for the given report service. Each report service has a
     * different set of options since filters are bound to the data generated in the report and each report uses different data.
     * @return FindIndexOptions
     */
    public function getSupportedOptions();
}
