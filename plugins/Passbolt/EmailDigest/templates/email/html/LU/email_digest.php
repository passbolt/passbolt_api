<?php
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
 * @since         3.0.0
 */
/**
 * This is the default template used by any email digest
 * Its content is defined by the class which created the digest
 *
 * @var \App\View\AppView $this
 * @var array $body
 */
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;

if (isset($body[EmailDigest::TPL_VAR_DIGEST_CONTENT])) {
    echo $body[EmailDigest::TPL_VAR_DIGEST_CONTENT];
}
