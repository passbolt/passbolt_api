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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Model\Dto;

/**
 * Class MfaDuoCallbackDto
 */
class MfaDuoCallbackDto
{
    /**
     * @var string|null $error
     */
    public $error;
    /**
     * @var string|null $errorDescription
     */
    public $errorDescription;
    /**
     * @var string|null $state
     */
    public $state;
    /**
     * @var string|null $duoCode
     */
    public $duoCode;

    /**
     * Construct the Dto based on array as source
     *
     * @param array $data The data source array to extract the information from.
     */
    public function __construct(array $data)
    {
        $this->error = $data['error'] ?? null;
        $this->errorDescription = $data['error_description'] ?? null;
        $this->state = $data['state'] ?? null;
        $this->duoCode = $data['duo_code'] ?? null;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !is_null($this->error) || !is_null($this->errorDescription);
    }

    /**
     * @return string
     */
    public function formatError(): string
    {
        $msg = empty($this->error)
            ? __('No Duo error provided')
            : $this->error;
        $msg .= ': ';
        $msg .= empty($this->errorDescription)
            ? __('No Duo error description provided')
            : $this->errorDescription;

        return $msg;
    }
}
