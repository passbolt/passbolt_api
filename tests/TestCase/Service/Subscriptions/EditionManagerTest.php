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
 * @since         5.12.0
 */
namespace App\Test\TestCase\Service\Subscriptions;

use App\BaseSolutionBootstrapper;
use App\Service\Subscriptions\EditionManager;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Passbolt\Ee\EeSolutionBootstrapper;
use RuntimeException;

/**
 * @covers \App\Service\Subscriptions\EditionManager
 */
class EditionManagerTest extends TestCase
{
    private ?EditionManager $sut = null;

    private ?string $originalEdition = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new EditionManager();
        $this->originalEdition = Configure::read('passbolt.edition');
    }

    public function tearDown(): void
    {
        if ($this->originalEdition !== null) {
            Configure::write('passbolt.edition', $this->originalEdition);
        } else {
            Configure::delete('passbolt.edition');
        }
        unset($this->sut);
        parent::tearDown();
    }

    public function testEditionManager_Boot_Ce(): void
    {
        Configure::write('passbolt.edition', EditionManager::EDITION_CE);
        $this->sut->boot();

        $this->assertSame(EditionManager::EDITION_CE, $this->sut->getEdition());
        $this->assertFalse($this->sut->isPro());
        $this->assertSame(BaseSolutionBootstrapper::class, $this->sut->getSolutionBootstrapperClass());
    }

    public function testEditionManager_Boot_Pro(): void
    {
        Configure::write('passbolt.edition', EditionManager::EDITION_PRO);
        $this->sut = new EditionManager();
        $this->sut->boot();

        $this->assertSame(EditionManager::EDITION_PRO, $this->sut->getEdition());
        $this->assertTrue($this->sut->isPro());
        $this->assertSame(EeSolutionBootstrapper::class, $this->sut->getSolutionBootstrapperClass());
    }

    public function testEditionManager_Boot_Idempotent(): void
    {
        Configure::write('passbolt.edition', EditionManager::EDITION_CE);
        $this->sut = new EditionManager();
        $this->sut->boot();

        $this->assertSame(EditionManager::EDITION_CE, $this->sut->getEdition());

        // Change config after boot — second boot should be a no-op
        Configure::write('passbolt.edition', EditionManager::EDITION_PRO);
        $this->sut->boot();

        $this->assertSame(EditionManager::EDITION_CE, $this->sut->getEdition());
        $this->assertFalse($this->sut->isPro());
        $this->assertSame(BaseSolutionBootstrapper::class, $this->sut->getSolutionBootstrapperClass());
    }

    public function testEditionManager_Boot_InvalidEdition(): void
    {
        Configure::write('passbolt.edition', 'invalid');
        $this->sut = new EditionManager();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Edition "invalid" does not exist');
        $this->sut->boot();
    }

    public function testEditionManager_Boot_MissingEditionConfig(): void
    {
        Configure::delete('passbolt.edition');
        $this->sut = new EditionManager();

        $this->expectException(RuntimeException::class);
        $this->sut->boot();
    }

    public function testEditionManager_DefaultState_BeforeBoot(): void
    {
        $this->sut = new EditionManager();

        $this->assertSame(EditionManager::EDITION_CE, $this->sut->getEdition());
        $this->assertFalse($this->sut->isPro());
        $this->assertNull($this->sut->getSolutionBootstrapperClass());
    }
}
