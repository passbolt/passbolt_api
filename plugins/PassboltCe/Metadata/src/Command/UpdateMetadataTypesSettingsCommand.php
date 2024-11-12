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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Command;

use App\Command\PassboltCommand;
use App\Error\Exception\FormValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\MetadataTypesSettingsAssertService;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use Passbolt\Metadata\Service\MetadataTypesSettingsSetService;

class UpdateMetadataTypesSettingsCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Create/update metadata types settings.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('username', [
                'short' => 'u',
                'required' => true,
                'help' => __('The user name (email).'),
            ])
            // Resources
            ->addOption(MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES, [
                'required' => false,
                'help' => __('Set default resource type format.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V5 resources.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V4 resources.'),
            ])
            // Folders
            ->addOption(MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE, [
                'required' => false,
                'help' => __('Set default folder type.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V5 folders.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V4 folders.'),
            ])
            // Tags
            ->addOption(MetadataTypesSettingsDto::DEFAULT_TAG_TYPE, [
                'required' => false,
                'help' => __('Set default tag type.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V5 tags.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V4 tags.'),
            ])
            // Comments
            ->addOption(MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE, [
                'required' => false,
                'help' => __('Set default comment type.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V5 comments.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS, [
                'required' => false,
                'help' => __('Set allow/disallow creation of V4 comments.'),
            ])
            ->addOption(MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE, [
                'required' => false,
                'help' => __('Set allow downgrade of V5 items to V4 setting.'),
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $user = $this->getUser($args);
        if (is_null($user)) {
            $io->abort(__('The user does not exist or is not active or is disabled.'));
        }

        $options = $this->getData($args);
        if ($options === []) {
            $io->abort(__('No metadata types settings provided. Please check available options.'));
        }

        $existingSettings = MetadataTypesSettingsGetService::getSettings();

        $uac = new UserAccessControl(Role::ADMIN, $user->id, $user->username);
        try {
            // Overwrite data to update
            $data = array_merge($existingSettings->toArray(), $options);

            // Validate data
            (new MetadataTypesSettingsAssertService())->assert($data);

            // Update the data
            (new MetadataTypesSettingsSetService())->saveSettings($uac, $data);
        } catch (FormValidationException $e) {
            $msg = __('Unable to update metadata types settings. See validation errors: ');
            $this->error($msg, $io);

            $this->displayValidationErrors($e->getErrors(), $io);

            return $this->errorCode();
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $io);

            return $this->errorCode();
        }

        $this->success(__('Metadata types settings have been updated.'), $io);

        return $this->successCode();
    }

    /**
     * @param \Cake\Console\Arguments $args Arguments.
     * @return \App\Model\Entity\User|null Return user entity if user is found, null otherwise.
     */
    private function getUser(Arguments $args): ?User
    {
        $username = $args->getOption('username');
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable
            ->findByUsername($username)
            ->find('activeNotDeleted')
            ->find('notDisabled')
            ->first();

        return $user;
    }

    /**
     * @param \Cake\Console\Arguments $args Arguments.
     * @return array
     */
    private function getData(Arguments $args): array
    {
        $allowedOptions = [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES,
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE,
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE,
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS,
            MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE,
        ];

        $data = [];
        foreach ($allowedOptions as $allowedOption) {
            $value = $args->getOption($allowedOption);
            // option not set, nothing to do
            if (is_null($value)) {
                continue;
            }

            $data[$allowedOption] = $value;
        }

        return $data;
    }

    /**
     * Displays validation exception errors into table format.
     *
     * @param array $errors Errors to display.
     * @param \Cake\Console\ConsoleIo $io IO object.
     * @return void
     */
    private function displayValidationErrors(array $errors, ConsoleIo $io): void
    {
        $result = [];

        // header
        $result[] = [__('Error field'), __('Error message')];
        foreach ($errors as $errorField => $fieldErrors) {
            $errorMsg = '';
            foreach ($fieldErrors as $fieldError) {
                $errorMsg = $fieldError;
                break; // Display 1 error per field
            }

            $result[] = [$errorField, $errorMsg];
        }

        $io->helper('Table')->output($result);
    }
}
