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
use Cake\Controller\Attribute\RequestToDto;
use Passbolt\Metadata\Model\Dto\MetadataPrivateKeysCreateManyDto;
use Passbolt\Metadata\Service\MetadataPrivateKeysCreateService;

class MetadataMissingPrivateKeysShareController extends AppController
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return parent::implementedEvents() + ['Controller.startup' => 'startup'];
    }

    /**
     * Asserts the request before it is mapped to the DTO.
     *
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if request is not JSON
     * @throws \Cake\Http\Exception\BadRequestException if request data is not an array or is empty
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an administrator
     */
    public function startup(): void
    {
        $this->assertJson();
        $this->assertNotEmptyArrayData();
        $this->User->assertIsAdmin();
    }

    /**
     * Share/create given missing private key(s) for one or more users.
     *
     * @param \Passbolt\Metadata\Model\Dto\MetadataPrivateKeysCreateManyDto $dto The private keys to create.
     * @return void
     */
    public function share(
        #[RequestToDto]
        MetadataPrivateKeysCreateManyDto $dto,
    ) {
        (new MetadataPrivateKeysCreateService())->createMany($this->User->getAccessControl(), $dto);

        $this->success(__('The operation was successful.'), []);
    }
}
