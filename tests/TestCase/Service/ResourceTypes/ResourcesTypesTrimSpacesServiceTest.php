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
 * @since         3.8.0
 */

namespace App\Test\TestCase\Service\ResourceTypes;

use App\Service\ResourceTypes\ResourceTypesTrimSpacesService;
use App\Test\Lib\AppTestCase;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesTypesTrimSpacesServiceTest extends AppTestCase
{
    public function testResourcesTypesTrimSpacesService()
    {
        $name = 'Standard Name';
        $slug = 'standard-slug';
        $resourceTypes = ResourceTypeFactory::make([
            [
                'name' => $name,
                'slug' => $slug . '-0',
            ],
            [
                'name' => "  $name    ",
                'slug' => $slug . '-1',
            ],
            [
                'name' => $name,
                'slug' => "  $slug-2     ",
            ],
            [
                'name' => "    $name    ",
                'slug' => "  $slug-3     ",
            ],
        ])->persist();

        (new ResourceTypesTrimSpacesService())->trim();

        foreach ($resourceTypes as $i => $resourceType) {
            $rt = ResourceTypeFactory::get($resourceType->id);
            $this->assertSame($name, $rt->name);
            $this->assertSame($slug . "-$i", $rt->slug);
        }
    }
}
