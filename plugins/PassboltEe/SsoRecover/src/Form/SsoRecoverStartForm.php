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
 * @since         3.11.0
 */
namespace Passbolt\SsoRecover\Form;

use App\Service\Users\UserRecoverServiceInterface;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validator;

class SsoRecoverStartForm extends Form
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('token', 'string')
            ->addField('case', 'string');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('case', true, __('The case field is required.'))
            ->add('case', 'invalidCase', [
                'rule' => function ($value, $context) {
                    return $value === UserRecoverServiceInterface::ACCOUNT_RECOVERY_CASE_DEFAULT;
                },
                'message' => __('The case is not supported. Only "default" case is supported.'),
            ]);

        $validator->requirePresence('token', true, __('The token field is required.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
