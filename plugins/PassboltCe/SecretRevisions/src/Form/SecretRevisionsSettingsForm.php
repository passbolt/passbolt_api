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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Form;

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class SecretRevisionsSettingsForm extends Form
{
    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('max_revisions', ['type' => 'integer'])
            ->addField('allow_sharing_revisions', ['type' => 'boolean']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('max_revisions', __('The max revisions should be a valid integer.'))
            ->requirePresence('max_revisions', true, __('The max revisions is required.'))
            ->greaterThan('max_revisions', 0, __('The max revisions should be greater than 0.'))
            ->add('max_revisions', 'maxRevisionsLimit', [
                'rule' => function ($value) {
                    $limit = Configure::read('passbolt.plugins.secretRevisions.maxRevisionsLimit');

                    return $value <= $limit;
                },
                'message' => __('The max revisions should not exceed the max revisions limit set by the administrator.'), // phpcs:ignore
            ]);

        $validator
            ->boolean('allow_sharing_revisions', __('The allow sharing revisions should be a valid boolean.'))
            ->requirePresence('allow_sharing_revisions', true, __('The allow sharing revisions is required.'))
            ->add('allow_sharing_revisions', 'isRevisionsSharingAllowed', [
                'rule' => function ($value) {
                    $enableAllowSharingRevisions = Configure::read('passbolt.plugins.secretRevisions.enableAllowSharingRevisions'); // phpcs:ignore

                    if (!$enableAllowSharingRevisions) {
                        return !$value;
                    }

                    return true;
                },
                'message' => __('The allow sharing revisions is not enabled by the administrator.'),
            ]);

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data);

        return parent::execute($data, $options);
    }

    /**
     * @param array $data Data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        return [
            'max_revisions' => $data['max_revisions'] ?? null,
            'allow_sharing_revisions' => $data['allow_sharing_revisions'] ?? null,
        ];
    }
}
