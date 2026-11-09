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
 * @since         5.16.0
 */

namespace App\Service\RecordsStrategy;

use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\ConflictException;
use Cake\ORM\Locator\LocatorAwareTrait;

abstract class AbstractMaxRecordsStrategy implements RecordsStrategyInterface
{
    use LocatorAwareTrait;

    /**
     * @return int
     */
    abstract protected function countRecords(): int;

    /**
     * @return int|null null if no maximum
     */
    abstract protected function getMax(): ?int;

    /**
     * @return string
     */
    abstract protected function getErrorMessage(): string;

    /**
     * @inheritDoc
     */
    public function assertAllowed(UserAccessControl $uac): void
    {
        if ($this->isMaxReached()) {
            throw new ConflictException($this->getErrorMessage());
        }
    }

    /**
     * @param string $configKey configuration key
     * @return int|null null if no maximum
     */
    protected function readMax(string $configKey): ?int
    {
        $max = Configure::read($configKey);

        return is_int($max) && $max >= 0 ? $max : null;
    }

    /**
     * @return bool
     */
    private function isMaxReached(): bool
    {
        $max = $this->getMax();
        if ($max === null) {
            return false;
        }

        return $this->countRecords() >= $max;
    }
}
