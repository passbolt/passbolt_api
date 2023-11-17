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

use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceDeleteEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;

/**
 * Create a new digest for all emails related to changes on resources.
 * It will aggregate the emails for Create, Update and Delete operations in the same digest.
 */
class ResourceChangesDigestTemplate extends AbstractDigestTemplate
{
    public const RESOURCE_CHANGES_TEMPLATE = 'LU/resources_change';

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return static::RESOURCE_CHANGES_TEMPLATE;
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsTheOperator(): string
    {
        return __('You made changes on several resources');
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsNotTheOperator(): string
    {
        return __('{0} has made changes on several resources', '{0}');
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            ResourceCreateEmailRedactor::TEMPLATE,
            ResourceUpdateEmailRedactor::TEMPLATE,
            ResourceDeleteEmailRedactor::TEMPLATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperatorVariableKey(): string
    {
        return 'user';
    }
}
