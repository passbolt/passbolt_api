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
 * @since         2.13.0
 */
namespace Passbolt\Folders\Test\Lib;

use App\Test\Lib\AppTestCase;

abstract class FoldersTestCase extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin('Folders');
        $this->loadPlugins([
            'Passbolt/Folders' => [],
            'Passbolt/EmailDigest' => [],
            'Passbolt/Locale' => [],
        ]);
    }
}
