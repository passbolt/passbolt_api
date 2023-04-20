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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\View\Helper;

use Cake\Core\Configure;

/**
 * HealthcheckHtmlHelper
 * Double shenanigans to reuse outputs from app/Console/healthcheckTask.php
 */
class HealthcheckHtmlHelper extends \App\View\Helper\HealthcheckHtmlHelper
{
    /**
     * Assert all the checks
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertEnvironment($checks = null)
    {
        parent::assertEnvironment($checks);

        $this->assert(
            $checks['webInstaller']['passboltConfigWritable'],
            __('The passbolt config is writable.'),
            __('The passbolt config is not writable.'),
            [
                __('Ensure the file ' . CONFIG . 'passbolt.php is writable by the webserver user.'),
                __('you can try:'),
                'sudo chown ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . CONFIG,
                'sudo chmod 775 $(find ' . CONFIG . ' -type d)',
            ]
        );

        $publicKeyPath = Configure::read('passbolt.gpg.serverKey.public');
        $this->assert(
            $checks['webInstaller']['publicKeyWritable'],
            __('The server OpenPGP public key file is writable.'),
            __('The server OpenPGP public key file is not writable.'),
            [
                __('Ensure the file {0} is writable by the webserver user.', CONFIG . 'gpg' . DS . $publicKeyPath),
                __('you can try:'),
                'sudo chown ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . CONFIG . 'gpg',
                'sudo chmod 775 $(find ' . CONFIG . 'gpg -type d)',
            ]
        );

        $privateKeyPath = Configure::read('passbolt.gpg.serverKey.private');
        $this->assert(
            $checks['webInstaller']['privateKeyWritable'],
            __('The server OpenPGP private key file is writable.'),
            __('The server OpenPGP private key file is not writable.'),
            [
                __('Ensure the file {0} is writable by the webserver user.', CONFIG . 'gpg' . DS . $privateKeyPath),
                __('you can try:'),
                'sudo chown ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . CONFIG . 'gpg',
                'sudo chmod 775 $(find ' . CONFIG . 'gpg -type d)',
            ]
        );
    }
}
