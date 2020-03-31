<?php

namespace Passbolt\Reports\Utility\ReportService;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
use Passbolt\Reports\Utility\Report;
use Passbolt\Reports\Utility\ReportServiceInterface;

abstract class AbstractReportService implements ReportServiceInterface
{
    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->getReportName(),
            'slug' => $this->getReportSlug(),
            'route' => Router::url('reports/' . $this->getReportSlug() . '.json', true),
            'description' => $this->getReportDescription(),
        ];
    }

    /**
     * Create a report and return it.
     *
     * @param User             $creator User who creates the report
     * @param FindIndexOptions $options Options to use to generate the report
     * @return Report
     */
    public function createReport(User $creator, FindIndexOptions $options = null)
    {
        return new Report(
            $this->getReportName(),
            $this->getReportDescription(),
            $this->getReportSlug(),
            $this->getReportTemplate(),
            new FrozenTime(),
            $creator,
            $this->getReportData($creator, $options)
        );
    }

    /**
     * @inheritDoc
     * @return FindIndexOptions
     */
    public function getSupportedOptions()
    {
        return new FindIndexOptions();
    }

    /**
     * Return the data to include in the report.
     *
     * @param User             $createdBy User who creates the report
     * @param FindIndexOptions $options Options to use to generate the report
     * @return array
     */
    abstract protected function getReportData(User $createdBy, FindIndexOptions $options = null);

    /**
     * Return a brief description of the generated report.
     *
     * @return string
     */
    abstract protected function getReportDescription();

    /**
     * Return the template associated to the generated report by the report generator.
     *
     * @return string
     */
    abstract protected function getReportTemplate();

    /**
     * Return the name of the report
     *
     * @return string
     */
    abstract protected function getReportName();
}
