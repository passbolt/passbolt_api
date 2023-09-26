<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.3.0
 */
namespace Passbolt\Ee\Service\Setup;

use App\Service\Setup\DefaultSetupStartService;
use App\Service\Setup\SetupStartUserInfoService;

class EeSetupStartService extends DefaultSetupStartService
{
    /**
     * @param \App\Service\Setup\SetupStartUserInfoService $recoverStartUserInfoService Gets the info relative to the user on setup start
     * @param \Passbolt\AccountRecovery\Service\Setup\SetupStartAccountRecoveryInfoService|string $setupStartAccountRecoveryInfoService Gets the info relative to the account recovery on setup start
     * @param \Passbolt\UserPassphrasePolicies\Service\Setup\SetupStartUserPassphrasePoliciesInfoService|string $setupStartUserPassphrasePoliciesInfoService Gets the info relative to the user passphrase policy on setup start
     */
    public function __construct(
        SetupStartUserInfoService $recoverStartUserInfoService,
        $setupStartAccountRecoveryInfoService,
        $setupStartUserPassphrasePoliciesInfoService
    ) {
        parent::__construct($recoverStartUserInfoService);
        $this->add($setupStartAccountRecoveryInfoService);
        $this->add($setupStartUserPassphrasePoliciesInfoService);
    }
}
