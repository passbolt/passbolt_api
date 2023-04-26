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
 * @since         3.12.2
 */

namespace App\Test\TestCase\Service\Resources;

use App\Service\Resources\ResourcesCleanDescriptionOnPasswordAndDescriptionTypeService;
use App\Test\Factory\ResourceFactory;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class ResourcesCleanDescriptionOnPasswordAndDescriptionTypeServiceTest extends TestCase
{
    public function testResourcesCleanDescriptionOnPasswordAndDescriptionTypeService()
    {
        $nResourcesToClean = rand(1, 5);
        $description = 'Foo';
        ResourceFactory::make($nResourcesToClean)
            ->setField('description', $description)
            ->setField('resource_type_id', UuidFactory::uuid('resource-types.id.password-and-description'))
            ->persist();

        ResourceFactory::make(2)
            ->setField('description', 'Bar')
            ->setField('resource_type_id', UuidFactory::uuid('bar'))
            ->persist();

        (new ResourcesCleanDescriptionOnPasswordAndDescriptionTypeService())->clean();
        $this->assertSame(0, ResourceFactory::find()->where(compact('description'))->count());
        $this->assertSame(2, ResourceFactory::find()->where(['description' => 'Bar'])->count());
    }
}
