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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Service\Cleanup;

use Passbolt\AccountRecovery\Service\Edition\AccountRecoveryDowngradeCleanupService;
use Passbolt\DirectorySync\Service\Edition\DirectorySyncDowngradeCleanupService;
use Passbolt\MfaPolicies\Service\Edition\MfaPoliciesDowngradeCleanupService;
use Passbolt\OfflineModePolicies\Service\Edition\OfflineModePoliciesDowngradeCleanupService;
use Passbolt\PasswordExpiryPolicies\Service\Edition\PasswordExpiryPoliciesDowngradeCleanupService;
use Passbolt\PasswordPoliciesUpdate\Service\Edition\PasswordPoliciesUpdateDowngradeCleanupService;
use Passbolt\Scim\Service\Edition\ScimDowngradeCleanupService;
use Passbolt\Sso\Service\Edition\SsoDowngradeCleanupService;
use Passbolt\Tags\Service\Edition\TagsDowngradeCleanupService;
use Passbolt\UserPassphrasePolicies\Service\Edition\UserPassphrasePoliciesDowngradeCleanupService;
use RuntimeException;

/**
 * Invokes every PRO plugin's downgrade cleanup service.
 *
 * Run inside the transaction opened by EditionDowngradeService. Any exception
 * thrown by a per-plugin cleanup propagates out of run() so the surrounding
 * transaction can roll back the entire downgrade.
 *
 * The list of cleanup services is hard-coded. Each entry must be the fully
 * qualified class name of a class implementing
 * EditionDowngradeCleanupServiceInterface. Entries whose class is not loadable
 * (typically because the PRO plugin is not installed on a CE-only build) are
 * skipped — there is no PRO data for absent plugins to clean up.
 */
class EditionDowngradeCleanupRunner
{
    /**
     * @return list<class-string<\Passbolt\Edition\Service\Cleanup\EditionDowngradeCleanupServiceInterface>>
     */
    protected function services(): array
    {
        return [
            AccountRecoveryDowngradeCleanupService::class,
            DirectorySyncDowngradeCleanupService::class,
            MfaPoliciesDowngradeCleanupService::class,
            PasswordExpiryPoliciesDowngradeCleanupService::class,
            PasswordPoliciesUpdateDowngradeCleanupService::class,
            ScimDowngradeCleanupService::class,
            SsoDowngradeCleanupService::class,
            TagsDowngradeCleanupService::class,
            UserPassphrasePoliciesDowngradeCleanupService::class,
            OfflineModePoliciesDowngradeCleanupService::class,
        ];
    }

    /**
     * Instantiates and runs every registered cleanup service.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->services() as $fqcn) {
            if (!class_exists($fqcn)) {
                continue;
            }

            $service = new $fqcn();
            if (!$service instanceof EditionDowngradeCleanupServiceInterface) {
                throw new RuntimeException(sprintf(
                    '%s must implement %s.',
                    $fqcn,
                    EditionDowngradeCleanupServiceInterface::class,
                ));
            }

            $service->cleanup();
        }
    }
}
