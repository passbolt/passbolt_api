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
 * @since         4.9.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Test\Factory\ResourceFactory;
use Passbolt\Folders\FoldersPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesIndexControllerTest extends TagPluginIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // We want to cover the general case where folder is enabled
        $this->enableFeaturePlugin(FoldersPlugin::class);
    }

    public function testResourcesIndexController_Contain_Tags()
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        TagFactory::make()->isPersonalFor($resource, $user)->persist();
        // Persist another tag, that should be ignored
        TagFactory::make()->persist();

        $this->getJson('/resources.json?contain[tag]=1');
        $this->assertSuccess();
        $tags = $this->_responseJsonBody[0]->tags;
        $this->assertSame(1, count($tags));
        $this->assertFalse($this->_responseJsonBody[0]->tags[0]->is_shared);
    }
}
