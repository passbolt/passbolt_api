<?php

namespace Passbolt\Reports\Utility\ReportService;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;
use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;

class EmployeeOnboardingReportService extends AbstractReportService
{
    const TEMPLATE = 'Passbolt/Reports.EmployeeOnboardingReport';
    const SLUG = 'employee-onboarding';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable|null $usersTable Instance of UsersTable
     */
    public function __construct(UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @return string
     */
    public function getReportSlug()
    {
        return static::SLUG;
    }

    /**
     * @return string
     */
    protected function getReportName()
    {
        return __('Employee Onboarding Report');
    }

    /**
     * @inheritDoc
     * @param FindIndexOptions $options Options to use to generate the report
     * @return array
     */
    protected function getReportData(User $createdBy, FindIndexOptions $options = null)
    {
        return [
            'users' => $this->usersTable->findNonActiveUsersAsList(),
        ];
    }

    /**
     * @return string
     */
    protected function getReportTemplate()
    {
        return static::TEMPLATE;
    }

    /**
     * @return string
     */
    protected function getReportDescription()
    {
        return __('List of all users who have never activated their account, or never logged in.');
    }
}
