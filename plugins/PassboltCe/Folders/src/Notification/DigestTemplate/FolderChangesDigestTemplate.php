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

namespace Passbolt\Folders\Notification\DigestTemplate;

use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;
use Passbolt\Folders\Notification\Email\CreateFolderEmailRedactor;
use Passbolt\Folders\Notification\Email\DeleteFolderEmailRedactor;
use Passbolt\Folders\Notification\Email\UpdateFolderEmailRedactor;

/**
 * Create a new digest for all emails related to changes on folders.
 * It will aggregate the emails for create, update, and delete operations in the same digest.
 */
class FolderChangesDigestTemplate extends AbstractDigestTemplate
{
    public const FOLDER_CHANGES_TEMPLATE = 'Passbolt/Folders.LU/folders_change';

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return static::FOLDER_CHANGES_TEMPLATE;
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsTheOperator(): string
    {
        return __('You made changes on several folders');
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsNotTheOperator(?string $operatorName = null): string
    {
        return __('{0} has made changes on several folders', $operatorName);
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            CreateFolderEmailRedactor::TEMPLATE,
            UpdateFolderEmailRedactor::TEMPLATE,
            DeleteFolderEmailRedactor::TEMPLATE,
            CreateFolderEmailRedactor::TEMPLATE_V5,
            UpdateFolderEmailRedactor::TEMPLATE_V5,
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
