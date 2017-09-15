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
use Cake\Routing\Router;
?>
<!-- header navigation -->
<header>
    <div class="header first ">
        <nav>
            <div class="top navigation primary">
                <ul>
                    <li class="home with-link"><a href="<?= Router::url('/') ?>"><span><?= __('home') ?></span></a></li>
                    <li class="left"><a href="<?= Router::url('/') ?>"><span><?= __('home') ?></span></a></li>
                    <li class="right"><a href="<?= Router::url('/login') ?>"><span><?= __('login') ?></span></a></li>
                    <li class="right"><a href="<?= Router::url('/register') ?>"><span><?= __('register') ?></span></a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
