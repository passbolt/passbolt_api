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
 * @since         4.5.0
 */

namespace App\Notification\DigestTemplate;

use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;

class GroupUserDeleteDigestTemplate extends AbstractDigestTemplate
{
    public const GROUPS_DELETE_TEMPLATE = 'LU/groups_delete';

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
        return __('{0} deleted several group memberships', '{0}');
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            GroupUserDeleteEmailRedactor::TEMPLATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperatorVariableKey(): string
    {
        return 'admin';
    }

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return static::GROUPS_DELETE_TEMPLATE;
    }
}
