<?php

namespace Passbolt\Reports\Utility;

use Passbolt\Reports\Factory\ReportServiceFactory;
use Passbolt\Reports\Utility\ReportService\EmployeeOnboardingReportService;
use Passbolt\Reports\Utility\ReportService\MfaEmployeeOnboardingReportService;

/**
 * Contains the list of all available report services.
 *
 * @package Passbolt\Reports\Utility
 */
class ReportServicePool
{
    /**
     * Return a an array of callable to instantiate to get a report service collection.
     * Each ReportServiceCollection is created callable to avoid to instantiate a report collection when it will
     * not be used.
     * @return callable[]
     */
    public function getReportServices()
    {
        return [
            EmployeeOnboardingReportService::SLUG => function (ReportServiceFactory $reportServiceFactory) {
                return $reportServiceFactory->createReportServiceCollection(
                    EmployeeOnboardingReportService::SLUG,
                    __('Employee Onboarding Report'),
                    __('List of all users who have never activated their account, or never logged in.'),
                    EmployeeOnboardingReportService::TEMPLATE,
                    [
                        new EmployeeOnboardingReportService(),
                    ]
                );
            },
            MfaEmployeeOnboardingReportService::SLUG => function (ReportServiceFactory $reportServiceFactory) {
                return $reportServiceFactory->createReportServiceCollection(
                    MfaEmployeeOnboardingReportService::SLUG,
                    __('MFA Employee Onboarding Report'),
                    __('List of all users who have never activated their account, or never logged in.'),
                    MfaEmployeeOnboardingReportService::TEMPLATE,
                    [
                        new MfaEmployeeOnboardingReportService(),
                    ]
                );
            },
        ];
    }
}
