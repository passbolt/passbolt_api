<?php
declare(strict_types=1);

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
namespace Passbolt\Tags\Test\Lib;

use App\Test\Lib\AppTestCase;
use CakephpFixtureFactories\ORM\FactoryTableRegistry;

abstract class TagTestCase extends AppTestCase
{
    protected $pluginEnabled;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        /**
         * @psalm-suppress InternalMethod
         * @psalm-suppress InternalClass
         */
        FactoryTableRegistry::getTableLocator()->clear();
        parent::setUp();
        $this->enableFeaturePlugin('Tags');
        $this->loadPlugins(['Passbolt/Tags' => []]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('Tags');
    }
}
