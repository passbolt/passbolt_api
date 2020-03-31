<?php

namespace Passbolt\Reports\Utility;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use Passbolt\Reports\Utility\ReportService\AbstractReportService;
use RuntimeException;

class ReportServiceCollection extends AbstractReportService implements ReportServiceInterface
{
    /**
     * @var ReportServiceInterface[]
     */
    private $reportServices = [];

    /**
     * @var string
     */
    private $reportName;

    /**
     * @var string
     */
    private $reportDescription;

    /**
     * @var string
     */
    private $reportSlug;

    /**
     * @var string
     */
    private $reportTemplate;

    /**
     * @param string $reportSlug Slug of the report
     * @param string $reportName Name of the report
     * @param string $reportDescription Description of the report
     * @param string $reportTemplate Template of the report
     */
    public function __construct(
        string $reportSlug,
        string $reportName,
        string $reportDescription,
        string $reportTemplate
    ) {
        $this->reportName = $reportName;
        $this->reportDescription = $reportDescription;
        $this->reportSlug = $reportSlug;
        $this->reportTemplate = $reportTemplate;
    }

    /**
     * @return string
     */
    public function getReportDescription()
    {
        return $this->reportDescription;
    }

    /**
     * @return string
     */
    public function getReportTemplate()
    {
        return $this->reportTemplate;
    }

    /**
     * @return string
     */
    public function getReportName()
    {
        return $this->reportName;
    }

    /**
     * Add a ReportGenerator instance to the list of report generators.
     *
     * @param ReportServiceInterface $reportService An instance of Report Generator
     * @return $this
     */
    public function add(ReportServiceInterface $reportService)
    {
        if (array_key_exists($reportService->getReportSlug(), $this->reportServices)) {
            throw new RuntimeException('Only one report generator for a given report type can be added.');
        }

        $this->reportServices[$reportService->getReportSlug()] = $reportService;

        return $this;
    }

    /**
     * @return string
     */
    public function getReportSlug()
    {
        return $this->reportSlug;
    }

    /**
     * @return FindIndexOptions
     */
    public function getSupportedOptions()
    {
        $options = [
            FindIndexOptions::FILTER_OPTION => [],
            FindIndexOptions::ORDER_OPTION => [],
            FindIndexOptions::CONTAIN_OPTION => [],
        ];

        foreach ($this->reportServices as $reportService) {
            $supportedOptions = $reportService->getSupportedOptions();
            $options = array_merge($options, $supportedOptions->getAllowedOptions());
        }

        return FindIndexOptions::createFromArray($options);
    }

    /**
     * @param User             $createdBy User which creates the report
     * @param FindIndexOptions $options Options to use to create the report
     * @return array|ReportServiceInterface[]
     */
    protected function getReportData(User $createdBy, FindIndexOptions $options = null)
    {
        $reports = [];

        foreach ($this->reportServices as $reportService) {
            $reports[] = $reportService->createReport($createdBy, $options);
        }

        return $reports;
    }
}
