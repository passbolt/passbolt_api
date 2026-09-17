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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Test\TestCase\Service;

use App\Test\Lib\AppTestCaseV5;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;
use Passbolt\Edition\Service\PopulateEditionService;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Test\DummySubscriptionTrait;

/**
 * @covers \Passbolt\Edition\Service\PopulateEditionService
 */
class PopulateEditionServiceTest extends AppTestCaseV5
{
    use DummySubscriptionTrait;

    private PopulateEditionService $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new PopulateEditionService();
    }

    public function tearDown(): void
    {
        if (file_exists(SubscriptionKeyGetService::SUBSCRIPTION_FILE)) {
            unlink(SubscriptionKeyGetService::SUBSCRIPTION_FILE);
        }
        unset($this->sut);
        parent::tearDown();
    }

    public function testPopulateEditionService_CE_NoSubscriptionKey(): void
    {
        $this->sut->populate();

        $result = EditionOrganizationSettingFactory::firstOrFail()->get('value');
        $this->assertSame(EditionDto::EDITION_CE, $result);
        $this->assertSame(1, EditionOrganizationSettingFactory::count());
    }

    public function testPopulateEditionService_Pro_ValidSubscriptionKeyInFile(): void
    {
        $this->setUpPathAndPublicSubscriptionKey();
        file_put_contents(SubscriptionKeyGetService::SUBSCRIPTION_FILE, $this->getValidSubscriptionKey());

        $this->sut->populate();

        $result = EditionOrganizationSettingFactory::firstOrFail()->get('value');
        $this->assertSame(EditionDto::EDITION_PRO, $result);
        $this->assertSame(1, EditionOrganizationSettingFactory::count());
    }

    public function testPopulateEditionService_Pro_ValidSubscriptionKeyInDatabase(): void
    {
        $this->setUpPathAndPublicSubscriptionKey();
        $this->persistValidSubscription();

        $this->sut->populate();

        $editionRow = EditionOrganizationSettingFactory::firstOrFail(
            ['property' => EditionOrganizationTable::PROPERTY_NAME],
        );
        $this->assertSame(EditionDto::EDITION_PRO, $editionRow->get('value'));
    }

    /**
     * Test scenario where passbolt.plugins.edition.subscriptionKey.public configuration isn't present.
     *
     * @return void
     */
    public function testPopulateEditionService_Pro_KeyInFileWithoutPublicKeyConfig(): void
    {
        file_put_contents(SubscriptionKeyGetService::SUBSCRIPTION_FILE, 'arbitrary-non-empty-key-bytes');

        $this->sut->populate();

        $editionRow = EditionOrganizationSettingFactory::firstOrFail(
            ['property' => EditionOrganizationTable::PROPERTY_NAME],
        );
        $this->assertSame(EditionDto::EDITION_PRO, $editionRow->get('value'));
    }

    public function testPopulateEditionService_CE_EmptyKeyFile(): void
    {
        file_put_contents(SubscriptionKeyGetService::SUBSCRIPTION_FILE, '   ');

        $this->sut->populate();

        $editionRow = EditionOrganizationSettingFactory::firstOrFail(
            ['property' => EditionOrganizationTable::PROPERTY_NAME],
        );
        $this->assertSame(EditionDto::EDITION_CE, $editionRow->get('value'));
    }

    public function testPopulateEditionService_ExistingRowUnchanged(): void
    {
        EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_PRO)
            ->persist();

        $this->sut->populate();

        $result = EditionOrganizationSettingFactory::firstOrFail()->get('value');
        $this->assertSame(EditionDto::EDITION_PRO, $result);
        $this->assertSame(1, EditionOrganizationSettingFactory::count());
    }
}
