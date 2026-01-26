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
 * @since         5.9.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

class EmailBatchResult
{
    private int $sent = 0;

    private int $failed = 0;

    /**
     * Increments the count of sent items.
     *
     * @param int $noOfEmails Number of emails sent.
     * @return void
     */
    public function recordSent(int $noOfEmails = 1): void
    {
        $this->sent += $noOfEmails;
    }

    /**
     * Increments the count of failed items.
     *
     * @param int $noOfEmails Number of emails threw exception.
     * @return void
     */
    public function recordFailed(int $noOfEmails = 1): void
    {
        $this->failed += $noOfEmails;
    }

    /**
     * Returns a number of sent items.
     *
     * @return int
     */
    public function sent(): int
    {
        return $this->sent;
    }

    /**
     * Returns a number of sent items.
     *
     * @return int
     */
    public function failed(): int
    {
        return $this->failed;
    }
}
