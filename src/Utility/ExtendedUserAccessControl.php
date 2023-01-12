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
 * @since         3.8.0
 */
namespace App\Utility;

use App\Utility\Validation\UserAgentValidation;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

/**
 * Class ExtendedUserAccessController
 *
 * Immutable UserAccessControl that also includes additional information such as user agent
 * and IP address
 *
 * @package App\Utility
 */
class ExtendedUserAccessControl extends UserAccessControl
{
    /**
     * @var string
     */
    private $userIp;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * UserAccessControl constructor.
     *
     * @param string $roleName The role name
     * @param string|null $userId the user uuid
     * @param string|null $username the user email
     * @param string|null $userIp the user ip
     * @param string|null $userAgent the user agent
     */
    public function __construct(
        string $roleName,
        ?string $userId = null,
        ?string $username = null,
        ?string $userIp = null,
        ?string $userAgent = null
    ) {
        parent::__construct($roleName, $userId, $username);

        if (!Validation::ip($userIp)) {
            throw new InternalErrorException('Failed extended user control. Invalid IP Address.');
        }
        $this->userIp = $userIp;

        if (!UserAgentValidation::isValid($userAgent)) {
            throw new InternalErrorException('Failed extended user control. Invalid user agent.');
        }
        $this->userAgent = $userAgent;
    }

    /**
     * Get the user ip address
     *
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * Get the user agent
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return array
     */
    public function getExtendedData(): array
    {
        return [
            'user_ip' => $this->userIp,
            'user_agent' => $this->userAgent,
        ];
    }
}
