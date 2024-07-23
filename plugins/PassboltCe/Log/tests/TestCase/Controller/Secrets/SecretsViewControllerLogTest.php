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

namespace Passbolt\Log\Test\TestCase\Controller\Secrets;

use App\Test\Factory\ResourceFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class SecretsViewControllerLogTest extends LogIntegrationTestCase
{
    public function testSecretsViewController_Secret_Access()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withSecretsFor([$user])
            ->withPermissionsFor([$user])
            ->persist();

        $secret = $resource->secrets[0];

        $this->getJson("/secrets/resource/$resource->id.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertSecretAttributes($this->_responseJsonBody);

        $secretAccess = SecretAccessFactory::firstOrFail([
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'secret_id' => $secret->id,
        ]);
        $this->assertNotNull($secretAccess);
    }
}
