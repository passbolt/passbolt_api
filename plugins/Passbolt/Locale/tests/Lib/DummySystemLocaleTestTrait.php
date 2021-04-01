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
use Passbolt\Locale\Service\LocaleService;

trait DummySystemLocaleTestTrait
{
    /**
     * Add a dummy system locale.
     */
    public function addFooSystemLocale(): void
    {
        Configure::write(
            'passbolt.plugins.locale.options',
            LocaleService::getSystemLocales() + ['foo' => 'foo-FOO']
        );
    }

    /**
     * Remove a dummy system locale.
     */
    public function removeFooSystemLocale(): void
    {
        $options = Configure::read('passbolt.plugins.locale.options');
        unset($options['foo']);
        Configure::write('passbolt.plugins.locale.options', $options);
    }
}
