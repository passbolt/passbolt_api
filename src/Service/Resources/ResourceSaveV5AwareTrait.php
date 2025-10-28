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

use Cake\Core\Exception\CakeException;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;

/**
 * Trait ResourcesVersionValidationServiceTrait
 *
 * A utility trait to set the validation options when creating or patching resources
 * based on v4 or v5 format
 */
trait ResourceSaveV5AwareTrait
{
    /**
     * Returns options array to use while saving resource entity.
     *
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto DTO.
     * @return array
     */
    public function getOptionsForResourceSave(MetadataResourceDto $resourceDto): array
    {
        return [
            'accessibleFields' => $this->getAccessibleFields($resourceDto),
            'validate' => $this->getValidator($resourceDto),
            'associated' => [
                'Permissions' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'aco' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true,
                    ],
                ],
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'user_id' => true,
                        'data' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Accessible fields array for resource save.
     *
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto DTO.
     * @return array
     */
    private function getAccessibleFields(MetadataResourceDto $resourceDto): array
    {
        $isV5 = $resourceDto->isV5();
        $fields = [];

        if ($isV5) {
            $metadataFields = MetadataResourceDto::V5_META_PROPS;
        } else {
            $metadataFields = MetadataResourceDto::V4_META_PROPS;
        }

        foreach ($metadataFields as $metadataField) {
            $fields[$metadataField] = true;
        }

        return $fields;
    }

    /**
     * Returns validator method to use (V4 or V5) while saving resource entity.
     *
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto DTO.
     * @return string
     */
    protected function getValidator(MetadataResourceDto $resourceDto): string
    {
        $isV5 = $resourceDto->isV5();
        if ($isV5) {
            $validator = 'v5';
            /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
            $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $ResourcesTable->rulesChecker();
            // In the case where the v5 rules where already defined on the table,
            // the following line will throw an exception. There is no way for Cake to detect
            // if a rule is already defined, so we need to catch the exception and continue.
            try {
                $ResourcesTable->buildRulesV5($rules);
            } catch (CakeException $e) {
            }
        } else {
            $validator = 'default';
        }

        return $validator;
    }
}
