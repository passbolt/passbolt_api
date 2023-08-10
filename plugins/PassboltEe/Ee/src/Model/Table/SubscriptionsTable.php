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
 * @since         3.1.0
 */
namespace Passbolt\Ee\Model\Table;

use App\Model\Entity\OrganizationSetting;
use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Ee\Model\Entity\Subscription;

/**
 * @method \Passbolt\Ee\Model\Entity\Subscription newEmptyEntity()
 * @method \Passbolt\Ee\Model\Entity\Subscription newEntity(array $data, array $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription get($primaryKey, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Ee\Model\Entity\Subscription[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SubscriptionsTable extends OrganizationSettingsTable
{
    /**
     * Default validation rules.
     * Validates the subscription format and content
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        return parent::validationDefault($validator);
    }

    /**
     * Parent validation rules. It is placed in a different method
     * in order to call it independently when persisting data in the tests, without having
     * the validation of the subscription key performed.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationParent(Validator $validator): Validator
    {
        return parent::validationDefault($validator);
    }

    /**
     * Filter organization settings by property.
     *
     * @param \Cake\Event\Event $event Model.beforeFind event.
     * @param  \Cake\ORM\Query $query Any query performed on the present table.
     * @return \Cake\ORM\Query
     */
    public function beforeFind(Event $event, Query $query): Query
    {
        return $query->where([
            $this->aliasField('property_id') => $this->getPropertyId(),
        ]);
    }

    /**
     * Ensure that an administration is provided in options before saving.
     *
     * @param \Cake\Event\Event $event the event
     * @param \Passbolt\Ee\Model\Entity\Subscription $entity entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $options['uac'] ?? null;
        if (empty($uac) || !$uac->isAdmin()) {
            throw new UnauthorizedException(__('Only admin can create or update subscription information.'));
        }
        if ($entity->isNew()) {
            $entity->set('created_by', $uac->getId());
        }
        $entity->set('modified_by', $uac->getId());
    }

    /**
     * Fields property and property_id are fixed.
     *
     * @param \Cake\Event\Event $event the event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $data['property'] = $this->getProperty();
        $data['property_id'] = $this->getPropertyId();
        $data['value'] = trim($data['value'] ?? '');
        $data['value'] = trim($data['value'], '\'"');
    }

    /**
     * @return \Cake\Datasource\EntityInterface|array
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException
     */
    public function getOrFail()
    {
        try {
            return $this->find()->firstOrFail();
        } catch (\Exception $e) {
            throw new SubscriptionRecordNotFoundException();
        }
    }

    /**
     * @inheritDoc
     */
    public function exists($conditions = []): bool
    {
        return parent::exists($conditions);
    }

    /**
     * @param string $asciiKey Subscription key string.
     * @param \App\Utility\UserAccessControl $uac Reporting who is acting.
     * @return string the key as original string
     */
    public function create(string $asciiKey, UserAccessControl $uac): string
    {
        $subscription = $this->newEntity(['value' => $asciiKey]);
        $this->handleErrors($subscription);
        $this->saveOrFail($subscription, compact('uac'));

        return $subscription->get('value');
    }

    /**
     * @param string $asciiKey Subscription key string.
     * @param \App\Utility\UserAccessControl $uac Reporting who is acting.
     * @return string the key as original string
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException
     */
    public function update(string $asciiKey, UserAccessControl $uac): string
    {
        // The validation checks automatically the validity of this key
        $subscriptionEntity = $this->newEntity(['value' => $asciiKey]);
        $this->handleErrors($subscriptionEntity);

        try {
            $this->deleteOrFail($this->find()->firstOrFail());
        } catch (RecordNotFoundException $exception) {
            throw new SubscriptionRecordNotFoundException();
        }

        $this->saveOrFail($subscriptionEntity, compact('uac'));

        return $subscriptionEntity->get('value');
    }

    /**
     * @param string $asciiKey Subscription key string.
     * @param \App\Utility\UserAccessControl $uac Reporting who is acting.
     * @return string the key as original string
     */
    public function createOrUpdate(string $asciiKey, UserAccessControl $uac): string
    {
        if ($this->exists()) {
            return $this->update($asciiKey, $uac);
        } else {
            return $this->create($asciiKey, $uac);
        }
    }

    /**
     * Throw Exceptions if errors were found in the validation.
     *
     * @param \Passbolt\Ee\Model\Entity\Subscription $subscription Subscription entity to be validated.
     * @return void
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionFormatException
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionValidationException
     * @throws \Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionException
     */
    public function handleErrors(Subscription $subscription): void
    {
        $formatError = $subscription->getError('value');
        if ($formatError) {
            throw new SubscriptionFormatException(
                __('The subscription key format is not valid.'),
                $formatError
            );
        }
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return OrganizationSetting::UUID_NAMESPACE . 'ee.subscription';
    }

    /**
     * @return string
     */
    public function getPropertyId(): string
    {
        return UuidFactory::uuid($this->getProperty());
    }
}
