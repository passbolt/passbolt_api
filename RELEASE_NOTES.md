Release song: https://www.youtube.com/watch?v=2YdC0GshApE

Passbolt v4.10.0 is a maintenance update that prepares for the upcoming v5 release, introducing beta support for the v5 resource type format within the v4 user interface and addressing reported issues.

This release is particularly valuable for maintainers of clients or integrations, offering an early preview of the v5 resource type format to aid in planning for future adaptations. While previous content types will remain supported until version 6, the new content types expand functionality, empowering technical teams to manage a broader range of credentials. Stay tuned—a blog article will be released soon to explain how to enable v5 support and begin testing your integrations.

Thank you to our community for your continued support.

## [4.10.0] - 2024-11-20
### Added
- PB-34458 Add v5 config flag PASSBOLT_V5_ENABLED
- PB-34459 Add metadata plugin
- PB-34450 Update resources table with metadata fields
- PB-34455 Update comments table with data field
- PB-34452 Update folders table with metadata fields
- PB-34454 Create metadata_private_keys table
- PB-34453 Create metadata_session_keys table
- PB-34456 Create metadata_keys table
- PB-34446 Add new resource_types entries for v5 resource types
- PB-34448 Update resource_types table to add deleted field
- PB-34472 Add GET/POST /metadata/settings.json endpoints
- PB-34465 Add MetadataPrivateKey entity
- PB-34466 Add MetadataPrivateKeysTable table
- PB-34460 Add MetadataKey entity
- PB-34462 Add MetadataKeysTable table
- PB-34461 As a logged-in user the settings.json provides information on the metadata plugin
- PB-34464 Cache key info in public key validation service for a single request
- PB-34467 Add POST /metadata/keys.json endpoint
- PB-34471 Add GET /metadata/keys endpoint
- PB-35259 Update support for created_by and modified_by for metadata keys
- PB-35163 Update DELETE /groups/<uuid>.json to support v5 resource format
- PB-35162 Update DELETE /users/<uuid>.json endpoint to clean up metadata private & session keys
- PB-35119 Add setup complete controller test (v5 key sharing)
- PB-35119 Start integration of user setup complete with v5 requirements
- PB-35122 Add support for v5 create, update resource entities
- PB-35152 Add DELETE /metadata/session-keys/<uuid>.json endpoint
- PB-35151 Add POST /metadata/session-keys.json endpoint
- PB-35150 Add GET /metadata/session-keys.json endpoint
- PB-34611 Add DELETE/PUT /resource-types/<uuid>.json endpoint
- PB-35365 Update POST /share/folders/<uuid>.json to support v5 logic
- PB-35363 Update GET /folders/<uuid>.json to support v5 format
- PB-35363 Update GET /folders.json to support v5 format
- PB-35921 Add API endpoint PUT /metadata/session-keys/<uuid>.json
- PB-35368 As a developer I can run a command to create metadata private key & share it with all users
- PB-35362 Update PUT /folders/<uuid>.json to support v5 format
- PB-35361 Update POST /folders.json to support v5 format
- PB-35120 Add healthcheck to try to decrypt the server metadata private key entry for the shared key
- PB-35165 Update POST /share/resources/<uuid>.json to support v5 logic
- PB-35166 Update email notification template to not include metadata (name, uri, etc.)
- PB-35166 Update POST /share/simulate/resources/<uuid>.json to support v5 logic
- PB-35157 Email changes for resources changes for V5
- PB-35157 Add validation for metadata fields
- PB-35160 Update GET /resources.json endpoint to support v5 format
- PB-35275 Add edit and create individual metadata private key endpoints
- PB-35171 Create a Service and CLI task to migrate v4 to v5 resources
- PB-35272 Add server settings to prevent edition of metadata settings and key
- PB-35260 Add signature verification for metadata private key sharing service
- PB-35277 As an administrator I must receive an email notification when a metadata key is added
- PB-35276 As an administrator I must receive an email notification when the metadata settings are updated
- PB-35751 As an administrators I can update the metadata settings using command line
- PB-35748 As an administrator I can run a command to migrate all the items to v5 format
- PB-35747 As an administrator I can run a command to migrate the folders to v5 format
- PB-35756 Update resource create endpoint to throw an error if allow_usage_of_personal_keys is set to false and personal key is used
- PB-35758 Update folders create/update endpoints to throw an error if allow_usage_of_personal_keys is set to false and personal key is used
- PB-35928 Add allow_v5_v4_downgrade to metadata types settings
- PB-35945 Add static method to cache and reuse MetadataTypesSettingsGetService results
- PB-35946 Add static method to cache and reuse MetadataKeysSettingsGetService results
- PB-35930 Update edit resource to support allow_v5_v4_downgrade settings
- PB-35931 Update edit folders to support allow_v5_v4_downgrade settings
- PB-35937 Add allow_v5_v4_downgrade settings to passbolt update_metadata_types_settings command
- PB-35084 Add the distribution/gpg information in the health-check
- PB-35866 Add OperatingSystemHealthcheck for 32 vs 64 bit
- PB-36228 ResourceCreateController should populate empty metadata_key_id if key type is user_key
- PB-36280 Add created_by and modified_by to metadata keys index service
- PB-34080 As an admin running the passbolt cleanup, I should delete duplicate resources_tags entries
- PB-36516 Add populatedMetadataUserKeyId request data massaging to folder create and update
- PB-36515 Add populatedMetadataUserKeyId request data massaging to resource edit
- PB-36558 Add baseline support for metadata key expiry
- PB-35085 Add TimeSyncHealthcheck for system clock sync status
- PB-36574 As a user I can delete a metadata key that is expired and not in use

### Improved
- PB-34609 Adds is-deleted filter and resources_count contain to ResourceTypesIndexController.php

### Security
- PB-35882 Bump cakephp/twig-view to 1.3.1 to get rid of twig security vulnerability warning
- PB-36609 Bump twig/twig composer package to v3.11.2
- PB-36609 Bump symfony/process composer package to v5.4.46

### Fixed
- PB-34189 Fix 500 on GET resources.json when passing 1 as parameter to some filters
- PB-35173 As a logged-in user I should not get a 500 if the folder does not exist
- PB-34481 Fix 500 error on /mfa/verify/{provider}.json on account with no 2FA set up
- PB-35669 Fix GenerateOpenPGPKeyService should default to GNUPGHOME environment variable if set
- PB-35724 Fix GenerateOpenPGPKeyService should generate key with empty passphrase
- PB-35709 Fix theme back to default randomly after refresh or navigation
- PB-35849 Fix API app does not update "Last logged in" time
- PB-35980 Fix has-parent filter returning duplicate resources (GITHUB #523)
- PB-36208 Fix LogFolderWritableHealthcheck help text paths

### Maintenance
- PB-34399 Bump singpolyma/openpgp-php package to v0.7
- PB-34305 Upgrade lockfile-lint library on passbolt_api package-lock.json
- PB-34306 Upgrade openpgp library on passbolt_api package-lock.json
- PB-33333 Refactor GroupUpdateControllerTest to use Fixture Factories
- PB-33332 Refactor ResourcesDeleteControllerTest to use Fixture Factories
- PB-33332 Refactor ResourcesUpdateControllerTest to use Fixture Factories
- PB-33332 Refactor ResourcesViewControllerTest to use Fixture Factories
- PB-33332 Refactor resource index controller test
- PB-22603 Refactor resources share service test with factories
- PB-33331 Add missing test cases for RecoverCompleteService
- PB-35433 Fix phpcs config to allow per file fixing in IDE
- PB-33330 Add missing test cases for SetupCompleteService
- PB-33329 Add missing test cases for RecoverAbortService
- PB-35777 Remove cloaking !empty() around method calls
- PB-35856 Fix up editorconfig for composer.json editing
- PB-35918 Bump composer/composer package to 2.8.1
- PB-34234 CI changes to use downstream repo
- PB-36605 Fix testVersionCommand_Compare_With_ChangeLogs failing test
- PB-35763 Refactor resource tags add controller
- PB-36607 Bump cakephp/cakephp composer package version to 4.5.7
