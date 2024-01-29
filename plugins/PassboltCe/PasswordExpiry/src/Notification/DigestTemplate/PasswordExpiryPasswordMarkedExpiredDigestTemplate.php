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

namespace Passbolt\PasswordExpiry\Notification\DigestTemplate;

use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;
use Passbolt\PasswordExpiry\Notification\Email\PasswordExpiryPasswordMarkedExpiredEmailRedactor;

/**
 * Create a new digest for all emails when a password is marked as expired.
 */
class PasswordExpiryPasswordMarkedExpiredDigestTemplate extends AbstractDigestTemplate
{
    public const PASSWORD_MARKED_EXPIRED_DIGEST_TEMPLATE = 'Passbolt/PasswordExpiry.LU/digest_password_marked_expired';

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return static::PASSWORD_MARKED_EXPIRED_DIGEST_TEMPLATE;
    }

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
        return __('{0} marked several passwords as expired', '{0}');
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            PasswordExpiryPasswordMarkedExpiredEmailRedactor::TEMPLATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperatorVariableKey(): string
    {
        return 'operator';
    }
}
