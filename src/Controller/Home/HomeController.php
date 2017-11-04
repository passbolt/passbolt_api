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
namespace App\Controller\Home;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;

class HomeController extends AppController
{
    /**
     * Password workspace page action
     *
     * @return void
     */
    public function index()
    {
        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('/Home')
            ->setTemplate('home');

        $this->set('title', Configure::read('passbolt.meta.description'));
        $this->set('jsBuildMode', Configure::read('passbolt.js.build'));

        // @TODO Backlog following tasks request to stop the cakephpconfig to be injected in the view for js application.
        // #PASSBOLT-2435
        // #PASSBOLT-2436
        $this->set('cakephpConfig', $this->_buildConfigToInject());

        $this->success();
    }

    /**
     * Build the cakephpConfig to inject in the view.
     * @return array
     */
    private function _buildConfigToInject()
    {
        // Retrieve the roles (user and admin).
        $this->loadModel('Roles');
        $roles = $this->Roles->find()
            ->where(['name IN' => ['user', 'admin']])
            ->combine('name', 'id');

        // Build the cakephp config to inject in the view.
        return [
            "app" => [
                "name" => Configure::read('App.name'),
                "description" => Configure::read('App.punchline'),
                "title" => Configure::read('App.title'),
                "version" => [
                    "number" => Configure::read('App.version.number'),
                    "name" => Configure::read('App.version.name')
                ],
                "url" => Router::fullBaseUrl() . '/',
                "debug" => Configure::read('debug'),
                "server_timezone" => date_default_timezone_get()
            ],
            "user" => [
                "id" => $this->User->id()
            ],
            "roles" => $roles,
            "image_storage" => [
                "public_path" => Configure::read('ImageStorage.publicPath')
            ]
        ];
    }
}
