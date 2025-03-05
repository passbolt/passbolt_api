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
namespace Passbolt\Metadata\Form\RotateKey;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\Metadata\Form\Upgrade\MetadataBatchUpgradeForm;
use Passbolt\Metadata\Model\Entity\MetadataKey;

class MetadataBatchRotateKeyForm extends MetadataBatchUpgradeForm
{
    /**
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return parent::_buildSchema($schema)
            ->addField('metadata_key', ['type' => 'array']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        parent::validationDefault($validator);

        $validator->add('metadata_key_type', 'is_not_shared_key', [
            'rule' => function ($value) {
                return $value === MetadataKey::TYPE_SHARED_KEY;
            },
            'message' => __('The metadata key type should be shared key.'),
        ]);

        $validator->array('metadata_key');
        $validator->addNested('metadata_key', $this->getMetadataKeyValidator());

        return $validator;
    }

    /**
     * Validations for metadata key nested values.
     *
     * @return \Cake\Validation\Validator
     */
    private function getMetadataKeyValidator(): Validator
    {
        $validator = new Validator();

        $validator->notEmptyDateTime('expired');

        $validator
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'metadata_key_deleted', [
                'rule' => ['equalTo', null],
                'message' => __('The metadata key should not be deleted.'),
            ]);

        return $validator;
    }
}
