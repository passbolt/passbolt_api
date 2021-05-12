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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Test\Lib;

use Cake\Core\Configure;

trait DummySystemLocaleTestTrait
{
    /**
     * Add a dummy system locale.
     */
    public function addFooSystemLocale(): void
    {
        $newOptions = array_merge(
            Configure::readOrFail('passbolt.plugins.locale.options'),
            [['locale' => 'foo','label' => 'foo-FOO',]]
        );
        Configure::write(
            'passbolt.plugins.locale.options',
            $newOptions
        );
    }

    /**
     * Remove a dummy system locale.
     */
    public function removeFooSystemLocale(): void
    {
        $options = Configure::readOrFail('passbolt.plugins.locale.options');
        array_pop($options);
        Configure::write('passbolt.plugins.locale.options', $options);
    }
}
