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
 * @since         5.18.0
 */
namespace Passbolt\TestData\Command\Base;

use App\Utility\UuidFactory;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;
use Passbolt\TestData\Lib\DataCommand;

class EditionDataCommand extends DataCommand
{
    protected bool $_truncate = false;
    public string $entityName = 'OrganizationSettings';

    /**
     * Get the edition data
     *
     * @return array
     */
    public function getData(): array
    {
        $editions[] = [
            'id' => UuidFactory::uuid('organization_setting.id.edition'),
            'property' => EditionOrganizationTable::PROPERTY_NAME,
            'property_id' => UuidFactory::uuid(EditionOrganizationTable::PROPERTY_NAME),
            'value' => EditionDto::EDITION_PRO,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin'),
        ];

        return $editions;
    }

    /**
     * Remove the edition row populated by the migrations, as the table holds a single edition row.
     *
     * @return void
     */
    public function beforeDataExecute(): void
    {
        $editionOrganizationTable = $this->fetchTable('OrganizationSettings');
        $editionOrganizationTable->deleteAll(['property' => EditionOrganizationTable::PROPERTY_NAME]);
    }
}
