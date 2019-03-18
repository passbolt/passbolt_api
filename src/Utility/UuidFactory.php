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

use Exception;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class UuidFactory
{
    const PASSBOLT_SEED = 'd5447ca1-950f-459d-8b20-86ddfdd0f922';

    /**
     * Return a UUID v4 or v5
     * Needed because CakePHP Text::uuid is not cryptographically secure
     * But also do not provide uuid5
     *
     * @param string|null $seed optional, used to create uuid5
     * @return string uuid4|uuid5
     */
    public static function uuid($seed = null)
    {
        if (!isset($seed)) {
            // Generate a version 4 (random) UUID object
            // uses random_bytes on php7
            // uses openssl_random_bytes on php5
            try {
                $uuid4 = Uuid::uuid4();

                return $uuid4->toString();
            } catch (UnsatisfiedDependencyException $e) {
                trigger_error('Cannot generate a random UUID, some dependencies are missing.', E_USER_ERROR);
            }
        } else {
            // Generate a version 5 (name-based and hashed with SHA1) UUID object
            $uuid5 = Uuid::uuid5(UuidFactory::PASSBOLT_SEED, $seed);

            return $uuid5->toString();
        }
    }
}
