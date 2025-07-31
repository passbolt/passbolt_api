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
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Exception;
use Passbolt\Scim\Exception\BadRequest;
use Passbolt\Scim\Exception\ConflictException;
use Passbolt\Scim\Exception\NotSupportedException;
use Passbolt\Scim\Exception\PreconditionFailedException;
use Passbolt\Scim\Exception\ResourceNotFoundException;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Model\Table\ScimEntriesTable;
use Passbolt\Scim\Utility\Object\Operation;
use Passbolt\Scim\Utility\Object\PatchRequest;
use Passbolt\Scim\Utility\Object\ServiceProviderConfig;
use Passbolt\Scim\Utility\ResourceInterface;
use Passbolt\Scim\Utility\Resources;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\Schemas;
use Passbolt\Scim\Utility\ScimConstants;
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
        $this->userEntity = $this->findExistingUserEntity(
            [$this->Users->aliasField('id') => $internalId],
            findDeleted: true
        );
        if (!$this->userEntity) {
            throw new ResourceNotFoundException(
                sprintf('The %s resource with id `%s` was not found', $this->getType(), $internalId)
            );
        }
        if ($this->userEntity->deleted) {
            throw new ResourceNotFoundException(
                sprintf('The %s resource with id `%s` is already deleted', $this->getType(), $internalId)
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
     * @param bool $findDeleted
     * @return \App\Model\Entity\User|null
     */
    protected function findExistingUserEntity(array $conditions = [], bool $findDeleted = false): ?User
    {
        if ($conditions === []) {
            if (!$this->email) {
                return null;
            }

            $conditions = [
                $this->Users->aliasField('username') => $this->email,
            ];
        }
        if (!$findDeleted) {
            $conditions[$this->Users->aliasField('deleted')] = false;
        }

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
            $scimUser = ScimTools::getScimSettingsSelectedUser();
            if (!$scimUser) {
                throw new ConflictException('Missing or invalid SCIM user in configuration settings');
            }
            $uac = new UserAccessControl($scimUser->role->name, $scimUser->id);
            try {
                $disabled = null;
                if (!$this->active) {
                    $disabled = $this->getDisabledDate();
                }
                $user = $this->Users->register([
                    'username' => $this->email,
                    'disabled' => $disabled,
                    'profile' => $profileData,
                ], $uac);
            } catch (ValidationException $exception) {
                throw new ConflictException(
                    $this->getValidationErrorMessage($exception->getEntity()),
                    scimType: ScimException::SCIM_TYPE_INVALID_VALUE
                );
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
                throw new ConflictException(
                    $this->getValidationErrorMessage($profile),
                    scimType: ScimException::SCIM_TYPE_INVALID_VALUE
                );
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
     * @return string
     */
    protected function getDisabledDate(): string
    {
        return DateTime::now()->format('Y-m-d H:i:s');
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
     * @return string|null
     * @throws \Exception
     */
    protected function getAttributeMutability(Operation $operation): ?string
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
                // set no used attributes as ATTRIBUTE_MUTABILITY_READ_WRITE to not trigger an error
                // this attributes will not be processed further int he process
                $mutability = ScimConstants::ATTRIBUTE_MUTABILITY_READ_WRITE;
        }
        if (!ScimConstants::isValidAttributeMutability((string)$mutability)) {
            throw new ScimException(sprintf('The mutability `%s` is invalid or not supported', $mutability));
        }

        return $mutability;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function update(PatchRequest $patchRequest): static
    {
        $serviceConfig = new ServiceProviderConfig();
        if (!$serviceConfig->isPatchSupported()) {
            throw new NotSupportedException('The PATCH operation is not supported');
        }
        if (!$this->userEntity) {
            throw new ScimException('The database user must be set to apply an operation');
        }


        $userPatchData = [];
        $scimEntryPatchData = [];
        foreach ($patchRequest->getOperations() as $operation) {
            $mutability = $this->getAttributeMutability($operation);
            if ($mutability === ScimConstants::ATTRIBUTE_MUTABILITY_READ_ONLY) {
                throw new BadRequest(sprintf(
                    'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                    $operation->getType(),
                    $operation->getAttribute(),
                    $mutability,
                ), scimType: ScimException::SCIM_TYPE_MUTABILITY);
            }
            if (
                $mutability === ScimConstants::ATTRIBUTE_MUTABILITY_IMMUTABLE &&
                $operation->getType() !== Operation::TYPE_ADD
            ) {
                throw new BadRequest(sprintf(
                    'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                    $operation->getType(),
                    $operation->getAttribute(),
                    $mutability,
                ), scimType: ScimException::SCIM_TYPE_MUTABILITY);
            }

            switch ($operation->getType()) {
                case Operation::TYPE_ADD:
                    switch ($operation->getAttribute()) {
                        case 'externalId':
                            if (empty($this->externalId)) {
                                $scimEntryPatchData['external_identifier'] = $operation->getValue();
                            }
                            break;
                        case 'userName':
                            if (empty($this->userName)) {
                                $scimEntryPatchData['scim_name'] = $operation->getValue();
                            }
                            break;
                        case 'name.givenName':
                            if (empty($this->firstName)) {
                                $userPatchData['profile']['first_name'] = $operation->getValue();
                            }
                            break;
                        case 'name.familyName':
                            if (empty($this->lastName)) {
                                $userPatchData['profile']['last_name'] = $operation->getValue();
                            }
                            break;
                        case 'active':
                            if ($this->active === null) {
                                $disabled = null;
                                if (!in_array($operation->getValue(), ['true','True'])) {
                                    $disabled = $this->getDisabledDate();
                                }
                                $userPatchData['disabled'] = $disabled;
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
                        case 'externalId':
                            $scimEntryPatchData['external_identifier'] = $operation->getValue();
                            break;
                        case 'userName':
                            $scimEntryPatchData['scim_name'] = $operation->getValue();
                            break;
                        case 'name.givenName':
                            $userPatchData['profile']['first_name'] = $operation->getValue();
                            break;
                        case 'name.familyName':
                            $userPatchData['profile']['last_name'] = $operation->getValue();
                            break;
                        case 'active':
                            $disabled = null;
                            $value = strtolower($operation->getValue());
                            if (!in_array($value, ['true', '1'])) {
                                $disabled = $this->getDisabledDate();
                            }
                            $userPatchData['disabled'] = $disabled;
                            break;
                        case 'emails':
                            throw new BadRequest(
                                'The email can not be changed',
                                scimType: ScimException::SCIM_TYPE_MUTABILITY
                            );
                        default:
                            // ignore attributes not used in this application
                    }
                    break;
                case Operation::TYPE_REMOVE:
                    switch ($operation->getAttribute()) {
                        case 'externalId':
                            $scimEntryPatchData['external_identifier'] = null;
                            break;
                        case 'userName':
                            $scimEntryPatchData['scim_name'] = null;
                            break;
                        case 'name.givenName':
                            $userPatchData['profile']['first_name'] = '';
                            break;
                        case 'name.familyName':
                            $userPatchData['profile']['last_name'] = '';
                            break;
                        case 'active':
                            $userPatchData['disabled'] = $this->getDisabledDate();
                            break;
                        case 'emails':
                            throw new BadRequest(
                                'The email can not be changed',
                                scimType: ScimException::SCIM_TYPE_MUTABILITY
                            );
                        default:
                            // ignore attributes not used in this application
                    }
                    break;
                default:
                    throw new NotSupportedException(
                        sprintf('The operation type `%s` is not supported or invalid', $operation->getType())
                    );
            }
        }

        $this->Users
            ->getConnection()
            ->transactional(function () use ($userPatchData, $scimEntryPatchData, $patchRequest) {
            if ($userPatchData) {
                $this->Users->patchEntity($this->userEntity, $userPatchData, [
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
                if (!$this->Users->save($this->userEntity, ['atomic' => false])) {
                    ScimLog::error('Unable to update the user from a PATCH request');
                    ScimLog::error(print_r($patchRequest, return: true));
                    ScimLog::error(print_r($this->userEntity, return: true));

                    throw new ConflictException(
                        $this->getValidationErrorMessage($this->userEntity),
                        scimType: ScimException::SCIM_TYPE_INVALID_VALUE
                    );
                }
            }
            if ($scimEntryPatchData) {
                $scimEntry = $this->userEntity->scim_entry ?? null;
                if (!$scimEntry) {
                    $scimEntry = $this->Users->ScimEntries->newEmptyEntity();
                    $scimEntryPatchData['foreign_model'] = ScimEntry::FOREIGN_MODEL_USERS;
                    $scimEntryPatchData['foreign_key'] = $this->userEntity->id;
                }
                $this->Users->ScimEntries->patchEntity($scimEntry, $scimEntryPatchData, [
                    'accessibleFields' => [
                        'foreign_key' => true,
                        'foreign_model' => true,
                        'external_identifier' => true,
                        'scim_name' => true,
                    ],
                ]);
                if (!$this->Users->ScimEntries->save($scimEntry, ['atomic' => false])) {
                    ScimLog::error('Unable to update the scim entry from a PATCH request');
                    ScimLog::error(print_r($patchRequest, return: true));
                    ScimLog::error(print_r($scimEntry, return: true));

                    throw new ConflictException(
                        $this->getValidationErrorMessage($scimEntry),
                        scimType: ScimException::SCIM_TYPE_INVALID_VALUE
                    );
                }
            }

            return true;
        });

        // Set the object properties with the updated information
        $this->setFromDatabase($this->userEntity->id);

        return $this;
    }

    /**
     * @param \Cake\ORM\Entity $entity
     * @return string
     */
    protected function getValidationErrorMessage(Entity $entity): string
    {
        if (!$entity->hasErrors()) {
            return '';
        }

        $message = '';
        // Add all errors in the same array level for easier formatting
        $validationErrors = $this->getValidationErrors($entity->getErrors());
        foreach ($validationErrors as $field => $errorMessage) {
            $message .= $field . ":\n" . $errorMessage . ":\n";
        }

        return trim($message);
    }

    /**
     * @param array $fieldErrors
     * @return array
     */
    protected function getValidationErrors(array $fieldErrors): array
    {
        $fieldNameMap = [
            'username' => __('Email'),
            'scim_name' => __('Username'),
            'scim_identifier' => __('External id'),
        ];
        $validationErrors = [];
        foreach ($fieldErrors as $fieldName => $errors) {
            foreach ($errors as $errorName => $errorMessage) {
                // this is not an error, it is an associated field errors
                if (is_array($errorMessage)) {
                    $validationErrors = array_merge($validationErrors, $this->getValidationErrors($errors));
                } else {
                    $fieldName = $fieldNameMap[$fieldName] ?? Inflector::humanize($fieldName);
                    $validationErrors[$fieldName][] = '    - ' . $errorMessage;
                }
            }
        }

        $formattedErrors = [];
        foreach ($validationErrors as $fieldName => $errors) {
            if (is_array($errors)) {
                $formattedErrors[$fieldName] = implode("\n", $errors);
            } else {
                $formattedErrors[$fieldName] = $errors;
            }
        }

        return $formattedErrors;
    }

    /**
     * @inheritDoc
     */
    public function delete(): static
    {
        if (!$this->userEntity) {
            throw new ScimException(
                sprintf('The values of the %s resource has not been set for the `delete` operation', $this->getType())
            );
        }

        try {
            $result = $this->Users->softDelete($this->userEntity);
            $errors = $this->userEntity->getErrors();
            if (!$result || $errors !== []) {
                if (isset($errors['id']['soleOwnerOfSharedContent'])) {
                    // @todo: send email
                    throw new ConflictException('The user cannot be deleted because its the sole owner of shared content');
                }
                throw new ConflictException('The User resource could not be deleted due to validation failure');
            }
        } catch (Exception $e) {
            ScimLog::error(sprintf('Unable to delete the user with id `%s`', $this->userEntity->id));
            ScimLog::error($e->getMessage());
            ScimLog::error($e->getTraceAsString());

            throw new ConflictException('Unexpected error when trying to delete the user.');
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
        if (empty($this->userEntity->scim_entry)) {
            $created = ScimTools::formatDateTimeToScim($this->userEntity->created);
            $modified = ScimTools::formatDateTimeToScim($this->userEntity->modified);
        } else {
            $created = ScimTools::formatDateTimeToScim($this->userEntity->scim_entry->created);
            $modified = ScimTools::formatDateTimeToScim($this->userEntity->scim_entry->modified);
        }

        return [
            'schemas' => [
                SchemaIdentifier::CORE_USER,
            ],
            'id' => $this->id,
            'externalId' => $this->externalId,
            'meta' => [
                'resourceType' => 'User',
                'created' => $created,
                'lastModified' => $modified,
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
