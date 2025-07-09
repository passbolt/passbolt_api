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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Resources\ResourceType;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Passbolt\Scim\Exception\ConflictException;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Utils\UserFormatterHelper;

/**
 * UserResourceType class
 */
class UserResourceType extends BaseResourceType
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    protected array $schemas = [
        'urn:ietf:params:scim:schemas:core:2.0:User',
    ];

    /**
     * User id
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * External id
     *
     * @var string|null
     */
    protected ?string $externalId = null;

    /**
     * Unique userName
     *
     * @var string|null
     */
    protected ?string $userName = null;

    /**
     * Email
     *
     * @var string|null
     */
    protected ?string $email = null;

    /**
     * First name
     *
     * @var string|null
     */
    protected ?string $firstName = null;

    /**
     * Last name
     *
     * @var string|null
     */
    protected ?string $lastName = null;

    /**
     * Is active user
     *
     * @var mixed|null
     */
    protected $active = null;

    /**
     * @inheritDoc
     */
    protected array $fieldMappings = [
        'id' => 'id',
        'externalId' => 'externalId',
        'userName' => 'userName',
        'email' => 'emails.{n}[type=work].value',
        'firstName' => 'name.givenName',
        'lastName' => 'name.familyName',
        'active' => 'active',
    ];

    /**
     * @var \App\Model\Table\UsersTable|\Cake\ORM\Table
     */
    protected UsersTable|Table $Users;

    /**
     * Formatter helper
     *
     * @var \Passbolt\Scim\Utils\UserFormatterHelper
     */
    protected UserFormatterHelper $formatter;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        /* @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
        $this->formatter = new UserFormatterHelper();
    }

    /**
     * @inheritDoc
     */
    public function setFromScim(array $data): self
    {
        // @todo: validate SCIM request
        foreach ($this->fieldMappings as $propertyName => $valuePath) {
            if (str_contains($valuePath, '{n}')) {
                $extractedData = Hash::extract($data, $valuePath);
                $value = $extractedData[0] ?? null;
            } else {
                $value = Hash::get($data, $valuePath);
            }
            $this->{$propertyName} = $value;
        }

        if (empty($this->externalId)) {
            $this->externalId = $this->userName;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(): self
    {
        if (!$this->email) {
            throw new \Exception('Missing `email` value for UserResource');
        }
        $adminUser = $this->Users->findFirstAdmin();
        $user = $this->checkExistingUser();

        // @todo: define an excel with the use cases of what to do if not exist, if exist but deleted, etc like DirectorySync?

        $userData = [
            'username' => $this->formatValue('email'),
            'deleted' => $this->formatValue('active'),
            'profile' => [
                'first_name' => $this->formatValue('firstName'),
                'last_name' => $this->formatValue('lastName'),
            ],
        ];
        $entryData = [
            'scim_name' => $this->formatValue('userName'),
            'external_identifier' => $this->formatValue('externalId'),
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
        ];
        if ($user === null) {
            $uac = new UserAccessControl(Role::ADMIN, $adminUser->get('id'));
            try {
                $user = $this->Users->register($userData, $uac);
            } catch (ValidationException $exception) {
                // @todo: parse validation errors in a user friendly message
                $message = 'Invalid Values: ' . json_encode($exception->getErrors());
                throw new ConflictException($message, scimType: ScimException::SCIM_TYPE_INVALID_VALUE);
            } catch (InternalErrorException $exception) {
                ScimLog::error($exception->getMessage());
                ScimLog::error($exception->getTraceAsString());
                throw new ConflictException('Unexpected error', scimType: ScimException::SCIM_TYPE_INVALID_VALUE);
            }
        }

        $entryData['foreign_key'] = $user->id;

        $scimEntry = $this->Users->ScimEntries->buildEntity($entryData);
        if (!$this->Users->ScimEntries->save($scimEntry)) {
            Log::debug(print_r($scimEntry->getErrors(), true));
            throw new ConflictException('Unable to save entry', scimType:ScimException::SCIM_TYPE_INVALID_VALUE);
        }

        $this->entity = $this->getUserForScim($user->id);

        return $this;
    }

    /**
     * Return the user entity with the information needed for SCIM structure conversion
     *
     * @param string $userId User Id
     * @return \App\Model\Entity\User
     */
    protected function getUserForScim(string $userId): User
    {
        return $this->Users->get(
            primaryKey: $userId,
            contain: ['Profiles', 'ScimEntries']
        );
    }

    /**
     * Format a field value
     *
     * @param string $fieldName Field Name
     * @param mixed|null $value Value
     * @return mixed
     */
    protected function formatValue(string $fieldName, $value = null)
    {
        if (!$value) {
            $value = $this->{$fieldName};
        }

        if (method_exists($this->formatter, $fieldName)) {
            return $this->formatter->{$fieldName}($value, $this);
        }

        return $value;
    }

    /**
     * Find SCIM entry
     *
     * @return \App\Model\Entity\User|null
     */
    protected function findExistingScimEntry(): ?User
    {
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users
            ->find()
            ->contain(['Profiles', 'ScimEntries'])
            ->matching('ScimEntries', function (Query $q) {
                return $q->where([
                    'ScimEntries.external_identifier' => (string)$this->externalId,
                ]);
            })
            ->first();

        return $user;
    }

    /**
     * Find existing entity
     *
     * @param array $conditions Conditions
     * @return \App\Model\Entity\User|null
     */
    protected function findExistingEntity(array $conditions = []): ?User
    {
        if (empty($conditions)) {
            $conditions = [
                'Users.username' => (string)$this->email,
            ];
        }
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users
            ->find()
            ->contain(['Profiles', 'ScimEntries'])
            ->where($conditions)->first();

        return $user;
    }

    /**
     * Find an existing user give the property fields
     *
     * @return \App\Model\Entity\User|null
     */
    protected function checkExistingUser(): ?User
    {
        $scimEntry = $this->findExistingScimEntry();
        if ($scimEntry) {
            throw new ConflictException(
                sprintf('A user with the same externalId `%s` already exists', $this->externalId),
                null,
                null,
                ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }

        $user = $this->findExistingEntity();
        if (!empty($user->scim_entry)) {
            throw new ConflictException(
                sprintf(
                    'The user with email `%s` is associated with another externalId `%s`',
                    $user->username,
                    $user->scim_entry->external_identifier,
                ),
                null,
                null,
                ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function setFromDatabase(EntityInterface $entity): self
    {
        $this->id = $entity->get('id');
        $this->entity = $entity;
        $this->externalId = $entity->get('scim_entry')?->get('external_identifier');
        $this->email = $entity->get('username');
        $this->userName = $entity->get('scim_entry')?->get('scim_name');
        $this->firstName = $entity->get('profile')?->get('first_name');
        $this->lastName = $entity->get('profile')?->get('last_name');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->entity->get('scim_entry');

        return [
            'schemas' => $this->schemas,
            'id' => $this->entity->get('id'),
            'externalId' => $this->externalId,
            'meta' => [
                'resourceType' => 'User',
                'created' => $scimEntry?->created->format('Y-m-d\TH:i:s.v\Z'),
                'lastModified' => $scimEntry?->modified->format('Y-m-d\TH:i:s.v\Z'),
            ],
            'userName' => $this->userName,
            'name' => [
                'formatted' => sprintf('%s %s', $this->firstName, $this->lastName),
                'familyName' => $this->lastName,
                'givenName' => $this->firstName,
            ],
            'active' => $this->formatter->active($this->entity->get('deleted'), $this),
            'emails' => [
                [
                    'value' => $this->entity->get('username'),
                    'type' => 'work',
                    'primary' => true,
                ],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getResource(string $resourceId): self
    {
        $user = $this->findExistingEntity(['Users.id' => $resourceId]);
        if (empty($user)) {
            throw new NotFoundException(sprintf('Resource %s not found', $resourceId));
        }

        return $this->setFromDatabase($user);
    }
}
