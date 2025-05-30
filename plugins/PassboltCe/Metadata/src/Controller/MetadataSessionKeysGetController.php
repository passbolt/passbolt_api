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
use Passbolt\Metadata\Service\MetadataSessionKeysGetService;

class MetadataSessionKeysGetController extends AppController
{
    /**
     * Metadata session keys GET action.
     *
     * @return void
     */
    public function get()
    {
        $this->assertJson();
        $uac = $this->User->getAccessControl();
        $metadataSessionKeys = (new MetadataSessionKeysGetService())->get($uac);
        $this->success(__('The operation was successful.'), $metadataSessionKeys);
    }
}
