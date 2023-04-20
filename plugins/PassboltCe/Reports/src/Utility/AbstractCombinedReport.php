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

abstract class AbstractCombinedReport extends AbstractReport implements CombinedReportInterface
{
    public const COMBINED_REPORT_TEMPLATE = 'Passbolt/Reports.CombinedReport';
    public const COMBINED_REPORT_TYPE = 'combined';

    /**
     * @var \Passbolt\Reports\Utility\ReportInterface[]
     */
    protected $subReports = [];

    /**
     * Return the template associated to the generated report by the report generator.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->template ?? self::COMBINED_REPORT_TEMPLATE;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return self::COMBINED_REPORT_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function addReport(ReportInterface $report): ReportInterface
    {
        $this->subReports[] = $report;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getReports(): array
    {
        return $this->subReports;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $data = [];
        foreach ($this->subReports as $reports) {
            $data[] = $reports->createReport();
        }

        return $data;
    }
}
