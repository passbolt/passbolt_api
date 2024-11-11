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
namespace Passbolt\Tags\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class MetadataResourcesTagsAddForm extends Form
{
    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('metadata', 'string')
            ->addField('metadata_key_id', 'string')
            ->addField('metadata_key_type', 'string')
            ->addField('is_shared', 'boolean');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('metadata')
            ->notEmptyString('metadata', __('The metadata should not be empty.'))
            ->ascii('metadata', __('The metadata should be a valid ASCII string.'));

        $validator
            ->allowEmptyString('metadata_key_id')
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'));

        $validator
            ->requirePresence('metadata_key_type')
            ->utf8Extended('metadata_key_type', __('The metadata key type should be a valid UTF8 string.')); // phpcs:ignore

        $validator
            ->requirePresence('is_shared')
            ->boolean('is_shared', __('The is shared should be a boolean type.'));

        return $validator;
    }
}
