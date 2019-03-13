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
namespace Passbolt\WebInstaller\Controller;

use App\Utility\Healthchecks;
use Cake\Controller\Controller;
use Cake\Core\Configure;

class GettingStartedController extends Controller
{
    /**
     * Getting started with passbolt.
     * Is displayed instead of any other page if passbolt is not configured.
     *
     * @return void
     */
    public function index()
    {
        $this->render('Pages/getting_started');
    }
}
