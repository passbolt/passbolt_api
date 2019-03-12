<?php
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
 * @since         2.0.0
 */
namespace App\Test\Lib\Model;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait AuthenticationTokenModelTrait
{
    /**
     * Asserts that an object has all the attributes a role should have.
     *
     * @param object $roles
     */
    protected function assertAuthTokenAttributes($roles)
    {
        $attributes = ['id', 'user_id', 'token', 'active', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $roles);
    }

    /**
     * @param array $data
     * @return string token
     */
    protected function saveDummyAuthToken(array $data)
    {
        $authToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $token = $authToken->newEntity(
            $data,
            ['accessibleFields' => [
                'user_id' => true,
                'token' => true,
                'active' => true,
                'type' => true,
                'data' => true,
                'created' => true,
                'deleted' => true
            ]]
        );
        $authToken->save($token, ['checkRules' => false]);

        return $token->token;
    }

    /**
     * @param $userId
     * @param $type
     * @param $case
     * @return string token
     */
    public function quickDummyAuthToken($userId, $type, $case = null)
    {
        $data = [
            'user_id' => $userId,
            'type' => $type,
            'token' => UuidFactory::uuid(),
            'active' => true
        ];
        switch ($case) {
            case 'inactive':
                $data['active'] = false;
                break;
            case 'expired_inactive':
                $data['active'] = false;
                $data['created'] = date('Y-m-d H:i:s', strtotime('-10 days'));
                $data['modified'] = date('Y-m-d H:i:s', strtotime('-10 days'));
                break;
            case 'expired':
                $data['created'] = date('Y-m-d H:i:s', strtotime('-10 days'));
                $data['modified'] = date('Y-m-d H:i:s', strtotime('-10 days'));
                break;
        }

        return $this->saveDummyAuthToken($data);
    }
}
