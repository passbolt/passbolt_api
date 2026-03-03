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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility\Resource;

use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Scim\Exception\BadRequestException;
use Passbolt\Scim\Exception\ConflictException;
use Passbolt\Scim\Exception\NotSupportedException;
use Passbolt\Scim\Exception\ResourceNotFoundException;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Log\ScimLog;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Model\Table\ScimEntriesTable;
use Passbolt\Scim\Model\Table\ScimUsersTable;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Passbolt\Scim\Utility\Object\Operation;
use Passbolt\Scim\Utility\Object\PatchRequest;
use Passbolt\Scim\Utility\Object\ServiceProviderConfig;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\Schemas;
use Passbolt\Scim\Utility\ScimConstants;
use Passbolt\Scim\Utility\ScimResourceInterface;
use Passbolt\Scim\Utility\ScimResources;
use Passbolt\Scim\Utility\ScimTools;

/**
 * UserResource class
 */
class UserScimResource implements ScimResourceInterface
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Scim\Model\Table\ScimUsersTable
     */
    protected ScimUsersTable $Users;

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
        /** @var \Passbolt\Scim\Model\Table\ScimUsersTable $scimUsersTable */
        $scimUsersTable = $this->fetchTable('Passbolt/Scim.ScimUsers');
        $this->Users = $scimUsersTable;
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
        return ScimResources::USERS;
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
        $this->validateScimUserData($data);

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
     * @param array $data
     * @return void
     */
    protected function validateScimUserData(array $data): void
    {
        $schemas = $data['schemas'] ?? [];
        if (!in_array(SchemaIdentifier::CORE_USER, $schemas)) {
            throw new BadRequestException('Invalid schema for SCIM User Resource');
        }
    }

    /**
     * @inheritDoc
     */
    public function setFromDatabase(string $internalId): self
    {
        if (!Validation::uuid($internalId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        $this->userEntity = $this->Users
            ->findForScim([$this->Users->aliasField('id') => $internalId], findDeleted: true)
            ->contain(['Profiles', 'ScimEntries'])
            ->first();
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
     * @inheritDoc
     */
    public function create(): static
    {
        $this->validateCreatePreconditions();

        $user = $this->Users->getConnection()->transactional(
            function (): User {
                $user = $this->findAndLockExistingUser();

                $this->assertNoScimEntryConflict($user);

                $user = $user
                    ? $this->updateExistingUserProfile($user)
                    : $this->registerNewUser();

                $this->createScimEntry($user);

                return $user;
            }
        );

        $this->setFromDatabase($user->id);

        return $this;
    }

    /**
     * Validate preconditions before attempting user creation.
     *
     * @throws \Passbolt\Scim\Exception\ConflictException
     */
    private function validateCreatePreconditions(): void
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
                    'The %s resource with id `%s` could not be created due to a uniqueness conflict',
                    $this->getType(),
                    $this->id
                ),
                scimType: ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }
    }

    /**
     * Find an existing user by email with a FOR UPDATE lock, loading associations.
     *
     * @return \App\Model\Entity\User|null
     */
    private function findAndLockExistingUser(): ?User
    {
        // Atomic locking between the uniqueness check and user insertion.
        // SELECT ... FOR UPDATE acquires gap locks on the username index range,
        // blocking concurrent INSERTs of the same username until this transaction commits.
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users
            ->findByEmailForScim($this->email, forUpdate: true)
            ->first();

        if ($user !== null) {
            // Load associations separately so that FOR UPDATE works with postgres/mysql both.
            $this->Users->loadInto($user, ['Profiles', 'ScimEntries']);
        }

        return $user;
    }

    /**
     * Throw if the existing user already has a SCIM entry.
     *
     * @param \App\Model\Entity\User|null $user
     * @throws \Passbolt\Scim\Exception\ConflictException
     */
    private function assertNoScimEntryConflict(?User $user): void
    {
        if (!empty($user->scim_entry)) {
            throw new ConflictException(
                sprintf(
                    'The %s resource with id `%s` could not be created due to a uniqueness conflict',
                    $this->getType(),
                    $user->id
                ),
                scimType: ScimException::SCIM_TYPE_UNIQUENESS,
            );
        }
    }

    /**
     * Register a brand-new user via the Users table.
     *
     * @return \App\Model\Entity\User
     * @throws \Passbolt\Scim\Exception\ConflictException
     */
    private function registerNewUser(): User
    {
        $scimUser = $this->getScimSettingsSelectedUser();
        if (!$scimUser) {
            throw new ConflictException(__('Missing or invalid SCIM user in configuration settings'));
        }

        $uac = new UserAccessControl($scimUser->role->name, $scimUser->id);

        try {
            return $this->Users->register([
                'username' => $this->email,
                'disabled' => $this->getDisabledValue($this->active),
                'profile' => [
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                ],
            ], $uac);
        } catch (ValidationException $exception) {
            throw new ConflictException(
                $this->getValidationErrorMessage($exception->getEntity()),
                scimType: ScimException::SCIM_TYPE_INVALID_VALUE
            );
        } catch (InternalErrorException $exception) {
            ScimLog::error($exception->getMessage());
            ScimLog::error($exception->getTraceAsString());
            throw new ConflictException(
                'Unexpected error',
                scimType: ScimException::SCIM_TYPE_INVALID_VALUE
            );
        }
    }

    /**
     * Update the profile of an existing user (user exists but has no SCIM entry yet).
     *
     * @param \App\Model\Entity\User $user
     * @return \App\Model\Entity\User
     * @throws \Passbolt\Scim\Exception\ConflictException
     */
    private function updateExistingUserProfile(User $user): User
    {
        /** @var \App\Model\Entity\Profile $profile */
        $profile = $user->profile;
        $this->Users->Profiles->patchEntity($profile, [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ], [
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

        return $user;
    }

    /**
     * Create a ScimEntry linking the user to the SCIM resource.
     *
     * @param \App\Model\Entity\User $user
     * @throws \Passbolt\Scim\Exception\ConflictException
     */
    private function createScimEntry(User $user): void
    {
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
                __('An unexpected error occurred while creating the user in the database'),
                scimType: ScimException::SCIM_TYPE_INVALID_VALUE,
            );
        }
    }

    /**
     * @return \App\Model\Entity\User|null
     */
    protected function getScimSettingsSelectedUser(): ?User
    {
        $scimConfig = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        if (empty($scimConfig['scim_user_id'])) {
            return null;
        }

        return $this->Users
            ->find()
            ->contain(['Roles'])
            ->where([$this->Users->aliasField('id') => $scimConfig['scim_user_id']])
            ->first();
    }

    /**
     * @param bool $isUserActive
     * @return string|null
     */
    protected function getDisabledValue(bool $isUserActive): ?string
    {
        if ($isUserActive) {
            return null;
        }

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
     * @param string $attribute
     * @return string|null
     * @throws \Exception
     */
    protected function getAttributeMutability(string $attribute): ?string
    {
        $userSchema = Schemas::build(SchemaIdentifier::CORE_USER)->toSCIM();
        switch ($attribute) {
            case 'name.givenName':
            case 'name.familyName':
                [$attributeName, $subAttributeName] = explode('.', $attribute);
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
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function patch(PatchRequest $patchRequest): static
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
            if ($operation->getAttribute() === null) {
                $attributes = $operation->getValue();
            } else {
                $attributes[$operation->getAttribute()] = $operation->getValue();
            }
            foreach ($attributes as $attributeName => $attributeValue) {
                $mutability = $this->getAttributeMutability($attributeName);
                if ($mutability === ScimConstants::ATTRIBUTE_MUTABILITY_READ_ONLY) {
                    throw new BadRequestException(sprintf(
                        'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                        $operation->getType(),
                        $attributeName,
                        $mutability,
                    ), scimType: ScimException::SCIM_TYPE_MUTABILITY);
                }
                if (
                    $mutability === ScimConstants::ATTRIBUTE_MUTABILITY_IMMUTABLE &&
                    $operation->getType() !== Operation::TYPE_ADD
                ) {
                    throw new BadRequestException(sprintf(
                        'Unable to apply operation `%s` for the attribute `%s` with mutability `%s`',
                        $operation->getType(),
                        $attributeName,
                        $mutability,
                    ), scimType: ScimException::SCIM_TYPE_MUTABILITY);
                }

                switch ($operation->getType()) {
                    case Operation::TYPE_ADD:
                        switch ($attributeName) {
                            case 'externalId':
                                if (empty($this->externalId)) {
                                    $scimEntryPatchData['external_identifier'] = $attributeValue;
                                }
                                break;
                            case 'userName':
                                if (empty($this->userName)) {
                                    $scimEntryPatchData['scim_name'] = $attributeValue;
                                }
                                break;
                            case 'name.givenName':
                                if (empty($this->firstName)) {
                                    $userPatchData['profile']['first_name'] = $attributeValue;
                                }
                                break;
                            case 'name.familyName':
                                if (empty($this->lastName)) {
                                    $userPatchData['profile']['last_name'] = $attributeValue;
                                }
                                break;
                            case 'active':
                                if ($this->active === null) {
                                    if (is_string($attributeValue)) {
                                        $value = in_array(strtolower($attributeValue), ['true', '1']);
                                    } else {
                                        $value = (bool)$attributeValue;
                                    }
                                    $userPatchData['disabled'] = $this->getDisabledValue($value);
                                }
                                break;
                            case 'emails':
                                throw new BadRequestException(
                                    'The email can not be changed',
                                    scimType: ScimException::SCIM_TYPE_MUTABILITY
                                );
                            default:
                                // ignore attributes not used in this application
                        }
                        break;
                    case Operation::TYPE_REPLACE:
                        switch ($attributeName) {
                            case 'externalId':
                                $scimEntryPatchData['external_identifier'] = $attributeValue;
                                break;
                            case 'userName':
                                $scimEntryPatchData['scim_name'] = $attributeValue;
                                break;
                            case 'name.givenName':
                                $userPatchData['profile']['first_name'] = $attributeValue;
                                break;
                            case 'name.familyName':
                                $userPatchData['profile']['last_name'] = $attributeValue;
                                break;
                            case 'active':
                                if (is_string($attributeValue)) {
                                    $value = in_array(strtolower($attributeValue), ['true', '1']);
                                } else {
                                    $value = (bool)$attributeValue;
                                }
                                $userPatchData['disabled'] = $this->getDisabledValue($value);
                                break;
                            case 'emails':
                                throw new BadRequestException(
                                    'The email can not be changed',
                                    scimType: ScimException::SCIM_TYPE_MUTABILITY
                                );
                            default:
                                // ignore attributes not used in this application
                        }
                        break;
                    case Operation::TYPE_REMOVE:
                        switch ($attributeName) {
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
                                $userPatchData['disabled'] = $this->getDisabledValue(isUserActive: false);
                                break;
                            case 'emails':
                                throw new BadRequestException(
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
        }

        $this->updateDatabaseUser($userPatchData, $scimEntryPatchData, $patchRequest);
        // Set the object properties with the updated information
        $this->setFromDatabase($this->userEntity->id);

        return $this;
    }

    /**
     * Update the user information in the database
     *
     * @param array $userPatchData
     * @param array $scimEntryPatchData
     * @param mixed $requestData
     * @return bool
     * @throws \Exception
     */
    protected function updateDatabaseUser(array $userPatchData, array $scimEntryPatchData, mixed $requestData): bool
    {
        return $this->Users
            ->getConnection()
            ->transactional(function () use ($userPatchData, $scimEntryPatchData, $requestData) {
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
                        ScimLog::error('Unable to update the user from the request data');
                        ScimLog::error(print_r($requestData, return: true));
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
                        ScimLog::error('Unable to update the scim entry from the request data');
                        ScimLog::error(print_r($requestData, return: true));
                        ScimLog::error(print_r($scimEntry, return: true));

                        throw new ConflictException(
                            $this->getValidationErrorMessage($scimEntry),
                            scimType: ScimException::SCIM_TYPE_INVALID_VALUE
                        );
                    }
                }

                return true;
            });
    }

    /**
     * Update the resource from a PUT request
     *
     * @param array $putRequestData
     * @throws \Exception
     */
    public function put(array $putRequestData): static
    {
        $this->validateScimUserData($putRequestData);
        $userPatchData = [];
        $scimEntryPatchData = [];

        $externalId = $putRequestData['externalId'] ?? null;
        if ($externalId !== $this->externalId) {
            $scimEntryPatchData['external_identifier'] = $externalId;
        }
        $userName = $putRequestData['userName'] ?? null;
        if ($userName !== $this->userName) {
            $scimEntryPatchData['scim_name'] = $userName;
        }
        $firstName = $putRequestData['name']['givenName'] ?? null;
        if ($firstName !== $this->firstName) {
            $userPatchData['profile']['first_name'] = $firstName;
        }
        $lastName = $putRequestData['name']['familyName'] ?? null;
        if ($lastName !== $this->lastName) {
            $userPatchData['profile']['last_name'] = $lastName;
        }
        $active = null;
        if (isset($putRequestData['active'])) {
            $active = (bool)$putRequestData['active'];
        }
        if ($active !== $this->active) {
            $userPatchData['disabled'] = $this->getDisabledValue((bool)$active);
        }

        $this->updateDatabaseUser($userPatchData, $scimEntryPatchData, $putRequestData);
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
            foreach ($errors as $errorMessage) {
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
                sprintf(
                    'The values of the %s resource has not been set for the `delete` operation',
                    $this->getType()
                )
            );
        }

        try {
            $result = $this->Users->softDelete($this->userEntity);
            $errors = $this->userEntity->getErrors();
            if (!$result || $errors !== []) {
                if (isset($errors['id']['soleOwnerOfSharedContent'])) {
                    // @todo: send email
                    throw new ConflictException(
                        'The user cannot be deleted because its the sole owner of shared content'
                    );
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
