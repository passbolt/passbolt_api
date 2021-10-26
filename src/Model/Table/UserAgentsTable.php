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
 * @since         2.0.0
 */
namespace App\Model\Table;

use App\Utility\Purifier;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use donatj\UserAgent\UserAgentParser;

/**
 * UserAgents Model
 *
 * @method \App\Model\Entity\UserAgent get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserAgent newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserAgent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserAgent findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UserAgent newEmptyEntity()
 * @method \App\Model\Entity\UserAgent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserAgent[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UserAgent[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UserAgent[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UserAgent[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UserAgentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->utf8('name', __('The name should be a valid BMP-UTF8 string.'))
            ->allowEmptyString('name');

        return $validator;
    }

    /**
     * Sanitize and parse the user agent string
     *
     * @param string|null $ua user agent (optional)
     * @return string|null
     */
    public function browserName(?string $ua = null): ?string
    {
        if ($ua == null) {
            $ua = Purifier::clean(env('HTTP_USER_AGENT'));
        }
        $browserName = 'undefined';
        try {
            $provider = new UserAgentParser();
            $userAgent = $provider->parse($ua);
            $browserName = $userAgent->browser();
        } catch (\Exception $e) {
        }

        return $browserName;
    }
}
