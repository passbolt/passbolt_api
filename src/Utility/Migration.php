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
namespace App\Utility;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;
use Migrations\Migrations;

class Migration
{
    protected $_remoteTagName;

    /**
     * Check if the app or plugins need a database migration
     *
     * @return bool
     */
    public static function needMigration()
    {
        $Migrations = new Migrations(['connection' => ConnectionManager::get('default')->configName()]);
        $migrations = $Migrations->status();
        foreach ($migrations as $i => $migration) {
            if ($migration['status'] === 'down') {
                return true;
            }
        }

        return false;
    }

    /**
     * Return true if the current installed version match the latest official one
     *
     * @return bool true if installed version is the latest
     */
    public static function isLatestVersion()
    {
        $remoteVersion = ltrim(Migration::getLatestTagName(), 'v');
        $localVersion = ltrim(Configure::read('passbolt.version'), 'v');

        return version_compare($localVersion, $remoteVersion, ">=");
    }

    /**
     * Return the current master version according to the official passbolt repository
     *
     * @throws \Exception if the github repository is not reachable
     * @throws \Exception if the tag information cannot be retrieved
     * @return string tag name such as 'v1.0.1'
     */
    public static function getLatestTagName()
    {
        $remoteTagName = Configure::read('passbolt.remote.version');
        if (is_null($remoteTagName)) {
            $url = 'https://api.github.com/repos/passbolt/passbolt_api/tags';
            try {
                $HttpSocket = new Client();
                $results = $HttpSocket->get($url);
            } catch (\Exception $e) {
                throw new \Exception(__('Could not connect to github repository'));
            }
            $tags = json_decode($results->getStringBody(), true);
            if (!isset($tags[0]) || !isset($tags[0]['name'])) {
                throw new \Exception(__('Could not read tag information on github repository'));
            }
            $remoteTagName = $tags[0]['name'];
            Configure::write('passbolt.remote.version', $remoteTagName);
        }

        return $remoteTagName;
    }
}
