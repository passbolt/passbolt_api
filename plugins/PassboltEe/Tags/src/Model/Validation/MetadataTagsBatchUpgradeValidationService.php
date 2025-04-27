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
namespace Passbolt\Tags\Model\Validation;

use Cake\ORM\Query;
use Passbolt\Metadata\Form\Upgrade\MetadataBatchUpgradeForm;
use Passbolt\Metadata\Model\Validation\MetadataBatchUpgradeValidationService;

class MetadataTagsBatchUpgradeValidationService extends MetadataBatchUpgradeValidationService
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return 'Passbolt/Tags.Tags';
    }

    /**
     * @inheritDoc
     */
    public function getForm(): MetadataBatchUpgradeForm
    {
        $form = new MetadataBatchUpgradeForm();
        $form->getValidator()
            ->remove('modified')
            ->remove('modified_by');

        return $form;
    }

    /**
     * @inheritDoc
     */
    protected function queryEntitiesFromIds(array $entityIds): Query
    {
        $tags = parent::queryEntitiesFromIds($entityIds);

        return $tags->select([
            'Tags.id',
            'Tags.metadata',
        ], true);
    }
}
