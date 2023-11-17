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
 * @since         4.4.1
 */

namespace Passbolt\Log\Test\TestCase\Controller\Resources;

use App\Test\Factory\UserFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class LogResourcesIndexControllerTest extends LogIntegrationTestCase
{
    public function testLogResourcesIndexController_Success_Contain_Secret(): void
    {
        $user = $this->logInAsUser();
        $otherUser = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretsFor([$user, $otherUser])
            ->persist();

        $urlParameter = 'contain[secret]=1';
        $this->getJson("/resources.json?$urlParameter");
        $this->assertSuccess();

        $conditions = [
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'secret_id' => $resource->secrets[0]->id,
        ];
        $this->assertSame(1, SecretAccessFactory::find()->where([$conditions])->count());
        $this->assertSame(1, SecretAccessFactory::count());
    }
}
