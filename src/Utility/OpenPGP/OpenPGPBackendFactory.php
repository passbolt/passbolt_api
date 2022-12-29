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
 * @since         2.10.0
 */
namespace App\Utility\OpenPGP;

use App\Utility\OpenPGP\Backends\Gnupg;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Http\Exception\InternalErrorException;

class OpenPGPBackendFactory
{
    public const HTTP = 'http';
    public const GNUPG = 'gnupg';

    /**
     * @var \App\Utility\OpenPGP\Backends\Gnupg|null
     */
    private static $instance;

    /**
     * Instantiate an OpenPGP Backend
     *
     * @param string $backend one of the supported backend
     * @throws \Cake\Http\Exception\InternalErrorException if backend if not supported
     * @return \App\Utility\OpenPGP\Backends\Gnupg
     */
    public static function create(string $backend = self::GNUPG): OpenPGPBackend
    {
        switch ($backend) {
            case self::GNUPG:
                try {
                    return new Gnupg();
                } catch (CakeException $exception) {
                    throw new InternalErrorException($exception->getMessage(), 500, $exception);
                }
                // no break
            default:
                throw new InternalErrorException('This OpenPGP backend is not supported');
        }
    }

    /**
     * Get a OpenPGP backend (Singleton pattern)
     *
     * @return \App\Utility\OpenPGP\Backends\Gnupg
     * @throws \Cake\Http\Exception\InternalErrorException if backend if not supported
     */
    public static function get()
    {
        if (self::$instance !== null) {
            return self::$instance;
        }
        self::$instance = self::create(Configure::read('passbolt.gpg.backend'));

        return self::$instance;
    }

    /**
     * Reset current instance
     * Useful if you want to change the config on the fly
     *
     * @return void
     */
    public static function reset()
    {
        self::$instance = null;
    }
}
