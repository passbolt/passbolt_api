<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
?><span style="font-weight:bold;"><?= $admin->profile->first_name; ?> <?php $admin->profile->last_name; ?>
     (<a href="mailto:<?= $admin->username; ?>" style="color:#888;text-decoration: underline;">
        <?= $admin->username; ?></a>)
</span>,<br>
<span style=""><?= __('just created an account for you on passbolt!'); ?></span><br>
<span style="color:#888888"><?= $user->created->i18nFormat(); ?></span><br>
