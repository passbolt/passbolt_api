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

use App\Utility\UserAccessControl;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

class MetadataResourcesAddExistingTagForm extends Form
{
    private UserAccessControl $uac;

    /**
     * @inheritDoc
     */
    public function __construct(UserAccessControl $uac)
    {
        parent::__construct();

        $this->uac = $uac;
    }

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('id', 'string');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id')
            ->uuid('id', __('The tag identifier should be a valid UUID.'))
            ->add('id', ['tagExists' => [
                'rule' => [$this, 'tagExists'],
                'message' => __('The tag is missing or does not belong to current user.'),
            ]]);

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(array $data): bool
    {
        return true;
    }

    /**
     * Check if tag exists, and belongs to the current user.
     *
     * @param string $tagId The tag identifier.
     * @param array|null $context Context data.
     * @return bool
     */
    public function tagExists(string $tagId, ?array $context = null): bool
    {
        // To prevent "SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type uuid" error in postgres
        if (!Validation::uuid($tagId)) {
            return false;
        }

        /** @var \Passbolt\Tags\Model\Table\TagsTable $tagsTable */
        $tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $query = $tagsTable->find();
        $tag = $query
            ->innerJoin(['ResourcesTags' => 'resources_tags'], [
                'ResourcesTags.tag_id' => new IdentifierExpression('Tags.id'),
            ])
            ->where([
                'Tags.id' => $tagId,
                'OR' => [
                    'ResourcesTags.user_id' => $this->uac->getId(),
                    $query->newExpr()->isNull('ResourcesTags.user_id'),
                ],
            ])
            ->first();

        return !is_null($tag);
    }
}
