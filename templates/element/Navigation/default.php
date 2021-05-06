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
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<!-- header navigation -->
<header>
    <div class="header first ">
        <nav>
            <div class="top navigation primary">
                <ul>
                    <li class="left"><a href="<?= Router::url('/', true) ?>"><span><?= __('home') ?></span></a></li>
                    <li class="right"><a href="<?= Router::url('/auth/login', true) ?>"><span><?= __('login') ?></span></a></li>
<?php if (Configure::read('passbolt.registration.public') === true) : ?>
                    <li class="right"><a href="<?= Router::url('/users/register', true) ?>"><span><?= __('register') ?></span></a></li>
<?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
