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
namespace Passbolt\Metadata\Controller\RotateKey;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;
use App\Utility\Pagination\PaginatePropertyAwareTrait;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Metadata\Service\Folders\MetadataFoldersRenderService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyFoldersUpdateService;

/**
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class MetadataRotateKeyFoldersPostController extends AppController
{
    use PaginatePropertyAwareTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    protected FoldersTable $Folders;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Folders = $this->fetchTable('Passbolt/Folders.Folders');
        $this->loadComponent('Passbolt/Metadata.MetadataPagination', [
            'model' => 'Folders',
            'order' => [
                'Folders.id' => 'asc', // Default sorted field
            ],
        ]);
    }

    /**
     * @return void
     */
    public function post()
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        (new MetadataRotateKeyFoldersUpdateService())->updateMany(
            $this->User->getAccessControl(),
            $this->getRequest()->getData()
        );

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $folders = $this->Folders->findMetadataRotateKeyIndex();
        $this->paginate($folders);
        $folders = $folders->all();
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();

        $folders = (new MetadataFoldersRenderService())->renderFolders($folders->toArray());
        $this->success(__('The operation was successful.'), $folders);
    }
}
