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
 * @since         4.3.0
 */

namespace Passbolt\Ee\Service\AccountRecoveryContinue;

use Passbolt\AccountRecovery\Service\AccountRecoveryContinue\AccountRecoveryContinueServiceInterface;

class AccountRecoveryContinueAggregatorService
{
    /**
     * @var array<\Passbolt\AccountRecovery\Service\AccountRecoveryContinue\AccountRecoveryContinueServiceInterface|string>
     */
    private array $services = [];

    /**
     * @param \Passbolt\UserPassphrasePolicies\Service\AccountRecovery\AccountRecoveryContinueUserPassphrasePoliciesService|string $accountRecoveryContinuePassphraseService Gets the info related to the user passphrase policy.
     */
    public function __construct($accountRecoveryContinuePassphraseService)
    {
        $this->services[] = $accountRecoveryContinuePassphraseService;
    }

    /**
     * Returns the result data.
     *
     * @return array|null
     */
    public function get(): ?array
    {
        $result = null;

        foreach ($this->services as $service) {
            if ($service instanceof AccountRecoveryContinueServiceInterface) {
                $result = $service->get();
            }
        }

        return $result;
    }
}
