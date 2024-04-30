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

namespace App\Service\Setup;

abstract class AbstractRecoverStartService
{
    /**
     * @var \App\Service\Setup\RecoverStartInfoServiceInterface[]
     */
    private array $services = [];

    /**
     * @param string $userId User uuid
     * @param string $token Register token
     * @return array|null Data to pass to the view
     */
    public function getInfo(string $userId, string $token): ?array
    {
        $result = null;

        foreach ($this->services as $service) {
            $result = $service->getInfo($userId, $token, $result);
        }

        return $result;
    }

    /**
     * Add service to get data from.
     *
     * @param \App\Service\Setup\RecoverStartInfoServiceInterface|string $service Add service that provide additional data to add into base service.
     * @return void
     */
    public function add($service): void
    {
        if ($service instanceof RecoverStartInfoServiceInterface) {
            $this->services[] = $service;
        }
    }
}
