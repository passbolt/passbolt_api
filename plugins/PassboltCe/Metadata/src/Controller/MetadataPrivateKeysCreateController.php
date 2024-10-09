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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Metadata\Service\MetadataPrivateKeysCreateService;

class MetadataPrivateKeysCreateController extends AppController
{
    /**
     * Share a metadata key with a given user
     *
     * @param string $id metadata key id
     * @return void
     */
    public function create(string $id)
    {
        $this->assertJson();
        $this->assertNotEmptyArrayData();
        $this->User->assertIsAdmin();

        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The metadata key identifier should be a UUID.'));
        }
        $data = $this->request->getData();

        $created = (new MetadataPrivateKeysCreateService())->create($this->User->getAccessControl(), $id, $data);
        $this->success(__('The operation was successful.'), $created);
    }
}
