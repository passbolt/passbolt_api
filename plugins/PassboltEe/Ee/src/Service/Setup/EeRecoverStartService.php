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

use App\Service\Setup\DefaultRecoverStartService;
use App\Service\Setup\RecoverStartUserInfoService;

class EeRecoverStartService extends DefaultRecoverStartService
{
    /**
     * @param \App\Service\Setup\RecoverStartUserInfoService $recoverStartUserInfoService Gets the info relative to the user on setup start
     * @param \Passbolt\AccountRecovery\Service\Setup\RecoverStartAccountRecoveryInfoService|string $recoverStartAccountRecoveryInfoService Gets the info relative to the account recovery on setup start
     * @param \Passbolt\UserPassphrasePolicies\Service\Setup\RecoverStartUserPassphrasePoliciesInfoService|string $recoverStartUserPassphrasePoliciesInfoService Gets the info relative to the user passphrase policy on setup start
     */
    public function __construct(
        RecoverStartUserInfoService $recoverStartUserInfoService,
        $recoverStartAccountRecoveryInfoService,
        $recoverStartUserPassphrasePoliciesInfoService
    ) {
        parent::__construct($recoverStartUserInfoService);
        $this->add($recoverStartAccountRecoveryInfoService);
        $this->add($recoverStartUserPassphrasePoliciesInfoService);
    }
}
