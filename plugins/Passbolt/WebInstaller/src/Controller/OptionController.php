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
namespace Passbolt\WebInstaller\Controller;

use Cake\Controller\Controller;

class OptionController extends Controller
{
    /**
     * Index
     */
    function index() {
        if(empty($this->request->getData())) {
            // Set default values.
            $this->request->data['full_base_url'] = $this->_guessFullBaseUrl();
            $this->set(['force_ssl' => $this->request->is('ssl') === true ? 1 : 0]);
        } else {
            // TODO.
            $session = $this->request->getSession();
            $session->write('Passbolt.Config.options', $this->request->getData());
            return $this->redirect('install/installation');
        }

        $this->render('Pages/options');
    }

    /**
     * Guess the full base url from the browser url.
     * @return string
     */
    private function _guessFullBaseUrl()
    {
        $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $currentURL .= $_SERVER["SERVER_NAME"];

        if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
            $currentURL .= ":".$_SERVER["SERVER_PORT"];
        }

        return $currentURL;
    }
}