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
 * @since         5.1.0
 */
namespace Passbolt\Tags\Controller\RotateKey;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;
use Passbolt\Tags\Model\Table\TagsTable;
use Passbolt\Tags\Service\Metadata\MetadataTagsRenderService;

class MetadataRotateKeyTagsIndexController extends AppController
{
    /**
     * @var \Passbolt\Tags\Model\Table\TagsTable
     */
    protected TagsTable $Tags;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
        $this->loadComponent('Passbolt/Metadata.MetadataPagination', [
            'model' => 'Tags',
            'order' => [
                'Tags.id' => 'asc', // Default sorted field
            ],
        ]);
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $tags = $this->Tags->findMetadataRotateKeyIndex();
        $tags = $this->paginate($tags)->toArray();
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();

        $tags = (new MetadataTagsRenderService())->renderTags($tags);
        $this->success(__('The operation was successful.'), $tags);
    }
}
