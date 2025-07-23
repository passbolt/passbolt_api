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

namespace Passbolt\Scim\Utility\Resource;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Passbolt\Scim\Exception\ConflictException;
use Passbolt\Scim\Exception\NotSupportedException;
use Passbolt\Scim\Exception\PreconditionFailedException;
use Passbolt\Scim\Exception\ResourceNotFoundException;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Model\Table\ScimEntriesTable;
use Passbolt\Scim\Utility\Object\Operation;
use Passbolt\Scim\Utility\Object\ServiceProviderConfig;
use Passbolt\Scim\Utility\ResourceInterface;
use Passbolt\Scim\Utility\Resources;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\Schemas;
use Passbolt\Scim\Utility\ScimConstants;
use Passbolt\Scim\Utility\ScimObjectInterface;
use Passbolt\Scim\Utility\ScimTools;

/**
 * UserResource class
 */
class UserResource implements ResourceInterface
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @var \Passbolt\Scim\Model\Table\ScimEntriesTable
     */
    protected ScimEntriesTable $ScimEntries;

    /**
     * @var \App\Model\Entity\User|null
     */
    protected ?User $userEntity = null;

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
     * Middle name
     *
     * @var string|null
     */
    protected ?string $middleName = null;

    /**
     * Is active
     *
     * @var bool|null
     */
    protected ?bool $active = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Users = $this->fetchTable('Users');
        $this->ScimEntries = $this->fetchTable('Passbolt/Scim.ScimEntries');
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return Resources::USERS;
    }

    /**
     * Field mapping to fill the object properties from SCIM data
     *
     * NOTE:
     *   You can use `Hash::get` compatible paths for the maps
     *   For a value in an array with multiple elements, use `Hash::extract` compatible paths.
     *
     * Example:
     *  [
     *      'email' => 'emails.{n}[type=work][primary=1].value',
     *  ]
     *
     * @var array
     */
    protected array $fieldMappings = [
        'id' => 'id',
        'externalId' => 'externalId',
        'userName' => 'userName',
        'email' => 'emails.{n}[type=work].value',
        'firstName' => 'name.givenName',
        'lastName' => 'name.familyName',
        'middleName' => 'name.middleName',
        'active' => 'active',
    ];

    /**
     * @inheritDoc
     */
    public function setFromScim(array $data): static
    {
        $this->externalId = $data['externalId'] ?? null;
        $this->userName = $data['userName'] ?? null;
        $this->firstName = $data['name']['givenName'] ?? null;
        $this->lastName = $data['name']['familyName'] ?? null;
        $this->middleName = $data['name']['middleName'] ?? null;
        if (isset($data['active'])) {
            $this->active = (bool)$data['active'];
        }
        $emails = Hash::extract($data, 'emails.{n}[type=work].value');
        $this->email = $emails[0] ?? null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFromDatabase(string|int $internalId): self
    {
        $this->userEntity = $this->findExistingUserEntity([$this->Users->aliasField('id') => $internalId]);
        if (!$this->userEntity) {
            throw new ResourceNotFoundException(
                sprintf('The %s resource with id `%s` was not found', $this->getType(), $internalId)
            );
        }

        $this->id = $this->userEntity->id;
        $this->externalId = $this->userEntity->scim_entry?->external_identifier;
        $this->userName = $this->userEntity->scim_entry?->scim_name;
        $this->email = $this->userEntity->username;
        $this->firstName = $this->userEntity->profile?->first_name;
        $this->lastName = $this->userEntity->profile?->last_name;
        $this->active = !$this->userEntity->disabled;

        return $this;
    }

    /**
     * Find existing User entity
     *
     * @param array $conditions
     * @return \App\Model\Entity\User|null
     */
    protected function findExistingUserEntity(array $conditions = []): ?User
    {
        if ($conditions === []) {
            if (!$this->email) {
                return null;
            }

            $conditions = [
                $this->Users->aliasField('username') => $this->email,
            ];
        }
        $conditions[$this->Users->aliasField('deleted')] = false;

        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users
            ->find()
            ->contain(['Profiles', 'ScimEntries'])
            ->where($conditions)
            ->first();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function create(): static
    {
        if (!$this->email) {
            throw new ConflictException(
                sprintf('The email was not found for the %s resource', $this->getType()),
                scimType: ScimException::SCIM_TYPE_INVALID_VALUE,
            );
        }
        if ($this->id) {
            throw new ConflictException(
                sprintf(
                    'The %s resource with userName `%s` already exist with id `%s`',
                    $this->getType(),
                    $this->userName,
                    $this->id
                ),
                scimType: ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }
        $user = $this->findExistingUserEntity();
        if (!empty($user->scim_entry)) {
            throw new ConflictException(
                sprintf(
                    'The %s resource with userName `%s` already exist with id `%s`',
                    $this->getType(),
                    $this->userName,
                    $user->id
                ),
                scimType: ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }

        $profileData = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ];
        if (!$user) {
            // @todo: obtain admin configured in SCIM settings? for logs, etc..
            $adminUser = $this->Users->findFirstAdmin();
            $uac = new UserAccessControl(Role::ADMIN, $adminUser->get('id'));
            try {
                $disabled = null;
                if (!$this->active) {
                    $disabled = date('Y-m-d H:i:s');
                }
                $user = $this->Users->register([
                    'username' => $this->email,
                    'disabled' => $disabled,
                    'profile' => $profileData,
                ], $uac);
            } catch (ValidationException $exception) {
                // @todo: parse validation errors in a user friendly message
                $message = 'Invalid Values: ' . json_encode($exception->getErrors());
                throw new ConflictException($message, scimType: ScimException::SCIM_TYPE_INVALID_VALUE);
            } catch (InternalErrorException $exception) {
                ScimLog::error($exception->getMessage());
                ScimLog::error($exception->getTraceAsString());
                throw new ConflictException('Unexpected error', scimType: ScimException::SCIM_TYPE_INVALID_VALUE);
            }
        } else {
            // Update profile if user exit but entry dont
            $profile = $user->profile;
            $this->Users->Profiles->patchEntity($profile, $profileData, [
                'accessibleFields' => [
                    'first_name' => true,
                    'last_name' => true,
                ],
            ]);
            if (!$this->Users->Profiles->save($profile)) {
                // @todo: parse validation errors in a user friendly message
                $message = 'Invalid Values: ' . json_encode($profile->getErrors());
                throw new ConflictException($message, scimType: ScimException::SCIM_TYPE_INVALID_VALUE);
            }
        }

        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->ScimEntries->buildEntity([
            'scim_name' => $this->userName,
            'external_identifier' => $this->externalId,
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'foreign_key' => $user->id,
        ]);
        if (!$this->ScimEntries->save($scimEntry)) {
            Log::error('Unable to save the scim entity');
            Log::error(print_r($this->$scimEntry, return: true));

            throw new ConflictException(
                'An unexpected error occurred while creating the user in the database',
                scimType:ScimException::SCIM_TYPE_INVALID_VALUE,
            );
        }

        $this->setFromDatabase($user->id);

        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getNameAttributeSchema(): array
    {
        $userSchema = Schemas::build(SchemaIdentifier::CORE_USER)->toSCIM();

        return Hash::extract($userSchema, 'attributes.{n}[name=name]')[0] ?? [];
    }

    /**
     * @param \Passbolt\Scim\Utility\Object\Operation $operation
     * @return string
     * @throws \Exception
     */
    protected function getAttributeMutability(Operation $operation): string
    {
        $userSchema = Schemas::build(SchemaIdentifier::CORE_USER)->toSCIM();
        switch ($operation->getAttribute()) {
            case 'name.givenName':
            case 'name.familyName':
                [$attributeName, $subAttributeName] = explode('.', $operation->getAttribute());
                $nameAttribute = Hash::extract($userSchema, "attributes.{n}[name=$attributeName]")[0] ?? [];
                $attribute = Hash::extract($nameAttribute, "subAttributes.{n}[name=$subAttributeName]");
                $mutability = $attribute[0]['mutability'] ?? null;
                break;
            case 'active':
                $attribute = Hash::extract($userSchema, 'attributes.{n}[name=active]');
                $mutability = $attribute[0]['mutability'] ?? null;
                break;
            case 'emails':
                $emailsAttribute = Hash::extract($userSchema, 'attributes.{n}[name=emails]')[0] ?? [];
                $attribute = Hash::extract($emailsAttribute, 'subAttributes.{n}[name=value]');
                $mutability = $attribute[0]['mutability'] ?? null;
                break;
            default:
                // set no used attributes as read only
                $mutability = ScimConstants::ATTRIBUTE_MUTABILITY_READ_ONLY;
        }
        if (!ScimConstants::isValidAttributeMutability((string)$mutability)) {
            throw new ScimException(sprintf('The mutability `%s` is invalid or not supported', $mutability));
        }

        return $mutability;
    }

    /**
     * @inheritDoc
     */
    public function applyOperation(Operation $operation): static
    {
        $serviceConfig = new ServiceProviderConfig();
        if (!$serviceConfig->isPatchSupported()) {
            throw new NotSupportedException('The PATCH operation is not supported');
        }
        if (!$this->userEntity) {
            throw new ScimException('The database user must be set to apply an operation');
        }

        $mutability = $this->getAttributeMutability($operation);
        // @todo: should we throw error or just ignore?
        if ($mutability === ScimConstants::ATTRIBUTE_MUTABILITY_READ_ONLY) {
            throw new ConflictException(sprintf(
                'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                $operation->getType(),
                $operation->getAttribute(),
                $mutability,
            ));
        }
        if (
            $mutability === ScimConstants::ATTRIBUTE_MUTABILITY_IMMUTABLE &&
            $operation->getType() !== Operation::TYPE_ADD
        ) {
            throw new ConflictException(sprintf(
                'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                $operation->getType(),
                $operation->getAttribute(),
                $mutability,
            ));
        }

        $patchData = [];
        switch ($operation->getType()) {
            case Operation::TYPE_ADD:
                switch ($operation->getAttribute()) {
                    case 'name.givenName':
                        if (empty($this->firstName)) {
                            $patchData['profile']['first_name'] = $operation->getValue();
                        }
                        break;
                    case 'name.familyName':
                        if (empty($this->lastName)) {
                            $patchData['profile']['last_name'] = $operation->getValue();
                        }
                        break;
                    case 'active':
                        if ($this->active === null) {
                            $disabled = null;
                            if (!in_array($operation->getValue(), ['true','True'])) {
                                $disabled = date('Y-m-d H:i:s');
                            }
                            $patchData['disabled'] = $disabled;
                        }
                        break;
                    case 'emails':
                        if (empty($this->email)) {
                            $subAttribute = $operation->getSubAttribute();
                            if ($subAttribute === 'value' && $operation->getComparisonExpression() === 'type eq work') {
                                $this->email = $operation->getValue();
                            }
                        }
                        break;
                    default:
                        // ignore attributes not used in this application
                }
                break;
            case Operation::TYPE_REPLACE:
                switch ($operation->getAttribute()) {
                    case 'name.givenName':
                        $patchData['profile']['first_name'] = $operation->getValue();
                        break;
                    case 'name.familyName':
                        $patchData['profile']['last_name'] = $operation->getValue();
                        break;
                    case 'active':
                        $disabled = null;
                        if (!in_array($operation->getValue(), ['true','True'])) {
                            $disabled = date('Y-m-d H:i:s');
                        }
                        $patchData['disabled'] = $disabled;
                        break;
                    case 'emails':
                        throw new PreconditionFailedException('The email can not be changed');
                    default:
                        // ignore attributes not used in this application
                }
                break;
            case Operation::TYPE_REMOVE:
                switch ($operation->getAttribute()) {
                    case 'name.givenName':
                        $patchData['profile']['first_name'] = '';
                        break;
                    case 'name.familyName':
                        $patchData['profile']['last_name'] = '';
                        break;
                    case 'active':
                        $patchData['disabled'] = date('Y-m-d H:i:s');
                        break;
                    case 'emails':
                        throw new PreconditionFailedException('The email can not be changed');
                    default:
                        // ignore attributes not used in this application
                }
                break;
            default:
                throw new NotSupportedException(
                    sprintf('The operation type `%s` is not supported or invalid', $operation->getType())
                );
        }

        $this->Users->patchEntity($this->userEntity, $patchData, [
            'accessibleFields' => [
                'disabled' => true,
            ],
            'associated' => [
                'Profiles' => [
                    'validate' => 'register',
                    'accessibleFields' => [
                        'first_name' => true,
                        'last_name' => true,
                    ],
                ],
            ],
        ]);
        if (!$this->Users->save($this->userEntity)) {
            ScimLog::error('Unable to apply a SCIM patch operation');
            ScimLog::error(print_r($operation, return: true));
            ScimLog::error(print_r($this->userEntity, return: true));

            throw new PreconditionFailedException(
                'Unexpected error when trying to apply the patch operation in the database'
            );
        }
        $this->setFromDatabase($this->userEntity->id);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(): static
    {
        if (!$this->id) {
            throw new ScimException(
                sprintf('The values of the %s resource has not been set for the `delete` operation', $this->getType())
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        if (!$this->id) {
            throw new ScimException(
                sprintf(
                    'The values of the %s resource has not been set for the `toSCIM` operation',
                    $this->getType()
                )
            );
        }

        return [
            'schemas' => [
                SchemaIdentifier::CORE_USER,
            ],
            'id' => $this->id,
            'externalId' => $this->externalId,
            'meta' => [
                'resourceType' => 'User',
                'created' => ScimTools::formatDateTimeToScim($this->userEntity->scim_entry->created),
                'lastModified' => ScimTools::formatDateTimeToScim($this->userEntity->scim_entry->modified),
            ],
            'userName' => $this->userName,
            'active' => (bool)$this->active,
            'emails' => [
                [
                    'primary' => true,
                    'type' => 'work',
                    'value' => $this->email,
                ],
            ],
            'name' => [
                'formatted' => $this->formatFullName(),
                'familyName' => $this->lastName,
                'givenName' => $this->firstName,
                'middleName' => $this->middleName,
            ],
        ];
    }

    /**
     * Format the user full name
     *
     * @return string
     */
    protected function formatFullName(): string
    {
        $nameParts = [];
        if ($this->firstName) {
            $nameParts[] = $this->firstName;
        }
        if ($this->middleName) {
            $nameParts[] = $this->middleName;
        }
        if ($this->lastName) {
            $nameParts[] = $this->lastName;
        }

        return trim(implode(' ', $nameParts));
    }
}
