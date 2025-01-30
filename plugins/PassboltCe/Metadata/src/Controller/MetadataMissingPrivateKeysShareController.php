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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Controller;

use App\Controller\AppController;
use Passbolt\Metadata\Model\Dto\MetadataPrivateKeysCreateManyDto;
use Passbolt\Metadata\Service\MetadataPrivateKeysCreateService;

class MetadataMissingPrivateKeysShareController extends AppController
{
    /**
     * Share/create given missing private key(s) for one or more users.
     *
     * @return void
     */
    public function share()
    {
        $this->assertJson();
        $this->assertNotEmptyArrayData();
        $this->User->assertIsAdmin();

        $dto = new MetadataPrivateKeysCreateManyDto($this->getRequest()->getData());
        (new MetadataPrivateKeysCreateService())->createMany($this->User->getAccessControl(), $dto);

        $this->success(__('The operation was successful.'), []);
    }
}
