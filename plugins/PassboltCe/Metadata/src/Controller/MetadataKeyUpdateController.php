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
use App\Error\Exception\FormValidationException;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Metadata\Form\MetadataKeyUpdateForm;
use Passbolt\Metadata\Model\Dto\MetadataKeyUpdateDto;
use Passbolt\Metadata\Service\MetadataKey\MetadataKeyUpdateService;

class MetadataKeyUpdateController extends AppController
{
    /**
     * Update a given metadata key,
     * Used only to mark keys as expired
     *
     * @param string $id key uuid
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if the key does not exist or is already expired
     * @throws \Cake\Http\Exception\BadRequestException if the key format is invalid or some conditions are not met
     * @throws \Cake\Http\Exception\InternalErrorException if there was an issue during the save/delete
     */
    public function update(string $id): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The metadata key ID should be a valid UUID.'));
        }

        $form = new MetadataKeyUpdateForm();
        if (!$form->execute($this->getRequest()->getData())) {
            throw new FormValidationException(__('Could not validate the metadata key data.'), $form);
        }

        $dto = MetadataKeyUpdateDto::fromArray($form->getData());
        (new MetadataKeyUpdateService())->update($this->User->getAccessControl(), $id, $dto);
        $this->success(__('The operation was successful.'));
    }
}
