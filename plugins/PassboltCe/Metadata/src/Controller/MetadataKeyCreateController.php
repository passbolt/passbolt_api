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
use App\Error\Exception\FormValidationException;
use Passbolt\Metadata\Form\MetadataKeyForm;
use Passbolt\Metadata\Model\Dto\MetadataKeyDto;
use Passbolt\Metadata\Service\MetadataKeyCreateService;

class MetadataKeyCreateController extends AppController
{
    /**
     * Metadata key save action.
     *
     * @return void
     */
    public function create()
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        $form = new MetadataKeyForm();
        if (!$form->execute($this->getRequest()->getData())) {
            throw new FormValidationException(__('Could not validate the metadata key data.'), $form);
        }

        $dto = MetadataKeyDto::fromRequestData($form->getData());
        $uac = $this->User->getAccessControl();
        $metadataKey = (new MetadataKeyCreateService())->create($uac, $dto);

        $this->success(__('The operation was successful.'), $metadataKey);
    }
}
