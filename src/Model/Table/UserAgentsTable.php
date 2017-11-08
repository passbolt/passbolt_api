<?php
namespace App\Model\Table;

use App\Utility\Purifier;
use Aura\Intl\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use UserAgentParser\Provider\DonatjUAParser;
use UserAgentParser\Exception\NoResultFoundException;

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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->utf8('name', __('The user agent should be a UTF8 compatible string.'))
            ->allowEmpty('name');

        return $validator;
    }

    /**
     * Sanitize and parse the user agent string
     *
     * @return string
     */
    public function browserName($ua = null) {
        if ($ua == null) {
            $ua = Purifier::clean(env('HTTP_USER_AGENT'), 'nohtml');
        }
        try {
            $provider = new DonatjUAParser();
            $userAgent = $provider->parse($ua);
            $browserName = $userAgent->getBrowser()->getName();
        } catch (NoResultFoundException $e) {
            $browserName = 'undefined';
        }
        return $browserName;
    }

}
