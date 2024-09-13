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

namespace App\Service\Resources;

use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;

/**
 * Trait ResourcesVersionValidationServiceTrait
 *
 * A utility trait to set the validation options when creating or patching resources
 * based on v4 or v5 format
 */
trait Version5AwareValidationTrait
{
    /**
     * Adjust the accessible fields and validator
     * If a metadata field is present, validate according to v5
     * Else use the v4 fields
     *
     * @param array $accessibleFields non-meta related accessible fields
     * @param bool $isV5 is the data patched a v5 resource
     * @return array
     */
    protected function getValidationOptionsAndSetBuildRules(array $accessibleFields, bool $isV5): array
    {
        if ($isV5) {
            $metadataFields = MetadataResourceDto::V5_META_PROPS;
            $validator = 'v5';
            /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
            $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $ResourcesTable->rulesChecker();
            $ResourcesTable->buildRulesV5($rules);
        } else {
            $metadataFields = MetadataResourceDto::V4_META_PROPS;
            $validator = 'default';
        }
        foreach ($metadataFields as $metadataField) {
            $accessibleFields[$metadataField] = true;
        }

        return [
            'accessibleFields' => $accessibleFields,
            'validate' => $validator,
        ];
    }
}
