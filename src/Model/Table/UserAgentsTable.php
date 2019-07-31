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
namespace App\Model\Table;

use App\Utility\Purifier;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use UserAgentParser\Provider\DonatjUAParser;

/**
 * UserAgents Model
 *
 * @method \App\Model\Entity\UserAgent get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserAgent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserAgent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserAgent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent findOrCreate($search, callable $callback = null, $options = [])
 */
class UserAgentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_agents');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->utf8('name', __('The user agent should be a UTF8 compatible string.'))
            ->allowEmptyString('name');

        return $validator;
    }

    /**
     * Sanitize and parse the user agent string
     *
     * @param string $ua user agent (optional)
     * @return string
     */
    public function browserName(string $ua = null)
    {
        if ($ua == null) {
            $ua = Purifier::clean(env('HTTP_USER_AGENT'));
        }
        $browserName = 'undefined';
        try {
            $provider = new DonatjUAParser();
            $userAgent = $provider->parse($ua);
            $browserName = $userAgent->getBrowser()->getName();
        } catch (\Exception $e) {
        }

        return $browserName;
    }
}
