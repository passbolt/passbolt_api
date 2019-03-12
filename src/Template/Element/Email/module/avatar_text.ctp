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
 * @since         2.0.0
 */
use App\Utility\Purifier;
?><span style="font-weight:bold;"><?= Purifier::clean($first_name); ?> <?php Purifier::clean($last_name); ?>
     (<a href="mailto:<?= Purifier::clean($username); ?>" style="color:#888;text-decoration: underline;"><?= Purifier::clean($username); ?></a>)</span><br>
<span style=""><?= $text ?></span><br>
<span style="color:#888888"><?= $datetime->nice(); ?></span><br>
