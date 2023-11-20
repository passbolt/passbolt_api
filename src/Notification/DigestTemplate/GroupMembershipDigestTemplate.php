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
 * @since         2.13.0
 */

namespace App\Notification\DigestTemplate;

use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserUpdateEmailRedactor;
use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;

class GroupMembershipDigestTemplate extends AbstractDigestTemplate
{
    public const GROUP_USERS_CHANGES_TEMPLATE = 'LU/group_users_change';

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsTheOperator(): string
    {
        return $this->logErrorIfTheRecipientCannotBeTheOperator();
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsNotTheOperator(): string
    {
        return __('{0} updated your memberships in several groups', '{0}');
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            GroupUserAddEmailRedactor::TEMPLATE,
            GroupUserUpdateEmailRedactor::TEMPLATE,
            GroupUserDeleteEmailRedactor::TEMPLATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperatorVariableKey(): string
    {
        return 'user';
    }

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return self::GROUP_USERS_CHANGES_TEMPLATE;
    }
}
