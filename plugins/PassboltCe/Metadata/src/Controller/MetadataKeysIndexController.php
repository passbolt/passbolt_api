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
use Passbolt\Metadata\Service\MetadataKeysIndexService;

class MetadataKeysIndexController extends AppController
{
    /**
     * Metadata keys index action.
     *
     * @return void
     */
    public function index()
    {
        $this->assertJson();

        $options = $this->QueryString->get([
            'contain' => ['metadata_private_keys', 'creator', 'creator.profile'],
            'filter' => ['deleted', 'expired'],
        ]);

        $metadataKeys = (new MetadataKeysIndexService())->get(
            $this->User->id(),
            $options['contain'] ?? null,
            $options['filter'] ?? null
        );
        $this->success(__('The operation was successful.'), $metadataKeys->toArray());
    }
}
