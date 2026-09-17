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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Service;

use App\Utility\UserAccessControl;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;

/**
 * Persists the runtime edition flag into the single `edition` row of `organization_settings` via EditionOrganizationTable.
 */
class EditionSetService
{
    use LocatorAwareTrait;

    /**
     * Flips the edition flag to PRO.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @return \Cake\Datasource\EntityInterface The saved row.
     * @throws \Cake\Http\Exception\ForbiddenException When the UAC is not admin.
     * @throws \App\Error\Exception\CustomValidationException On validation failure.
     * @throws \Cake\Http\Exception\InternalErrorException When the save fails.
     */
    public function setToPro(UserAccessControl $uac): EntityInterface
    {
        return $this->save($uac, EditionDto::EDITION_PRO);
    }

    /**
     * Flips the edition flag to CE.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @return \Cake\Datasource\EntityInterface The saved row.
     * @throws \Cake\Http\Exception\ForbiddenException When the UAC is not admin.
     * @throws \App\Error\Exception\CustomValidationException On validation failure.
     * @throws \Cake\Http\Exception\InternalErrorException When the save fails.
     */
    public function setToCe(UserAccessControl $uac): EntityInterface
    {
        return $this->save($uac, EditionDto::EDITION_CE);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param string $edition One of `EditionDto::EDITION_CE` or `EditionDto::EDITION_PRO`.
     * @return \Cake\Datasource\EntityInterface The saved row.
     * @throws \Cake\Http\Exception\ForbiddenException When the UAC is not admin.
     */
    private function save(UserAccessControl $uac, string $edition): EntityInterface
    {
        $uac->assertIsAdmin();

        /** @var \Passbolt\Edition\Model\Table\EditionOrganizationTable $table */
        $table = $this->fetchTable('Passbolt/Edition.EditionOrganization');

        return $table->createOrUpdateSetting(
            EditionOrganizationTable::PROPERTY_NAME,
            $edition,
            $uac,
        );
    }
}
