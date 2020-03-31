<?php

namespace Passbolt\Reports\Factory;

use Exception;
use InvalidArgumentException;
use Passbolt\Reports\Utility\ReportServiceCollection;
use Passbolt\Reports\Utility\ReportServicePool;

/**
 * Create report service instance. Use the ReportServicePool.
 *
 * @package Passbolt\Reports\Factory
 */
class ReportServiceFactory
{
    /**
     * @var ReportServicePool
     */
    private $reportServicePool;

    /**
     * @param ReportServicePool $reportServicePool An instance of ReportServicePool
     */
    public function __construct(ReportServicePool $reportServicePool = null)
    {
        $this->reportServicePool = $reportServicePool ?? new ReportServicePool();
    }

    /**
     * @param string $slug Slug of the report
     * @param string $name Name of the report
     * @param string $description Description of the report
     * @param string $template Template of the report
     * @param array  $reportServices List of report which compose report
     * @return ReportServiceCollection
     */
    public function createReportServiceCollection(
        string $slug,
        string $name,
        string $description,
        string $template,
        array $reportServices
    ) {
        $reportServiceCollection = new ReportServiceCollection($slug, $name, $description, $template);

        foreach ($reportServices as $reportService) {
            $reportServiceCollection->add($reportService);
        }

        return $reportServiceCollection;
    }

    /**
     * Return a report service collection
     * @param string $reportSlug Slug of the report
     * @return ReportServiceCollection
     * @throws Exception
     */
    public function get(string $reportSlug)
    {
        $reportServices = $this->reportServicePool->getReportServices();
        $reportServiceCollection = $reportServices[$reportSlug] ?? false;

        if (!$reportServiceCollection) {
            throw new InvalidArgumentException();
        }

        return $reportServiceCollection($this);
    }
}
