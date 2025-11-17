# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [5.7.2] - 2025-11-17
### Fixed
- PB-46826 As an administrator running the cleanup task, the server metadata private key entry should not be deleted

## [5.7.2-test.1] - 2025-11-17
### Fixed
- PB-46826 As an administrator running the cleanup task, the server metadata private key entry should not be deleted

## [5.7.1] - 2025-11-14
### Fixed
- PB-46680 Fix DUO authentication form blocked by CSP header

## [5.7.1-test.1] - 2025-11-14
### Fixed
- PB-46680 Fix DUO authentication form blocked by CSP header

## [5.7.0] - 2025-11-12
### Added
- PB-46107 As an administrator I can define the number of past secret revisions persisted in DB
- PB-46109 As an administrator I can block the edition of the secret revisions settings with a configuration flag
- PB-46110 As a logged-in user I can view the past secret revisions of a resource
- PB-45059 As an administrator I can see in the healthcheck if zero knowledge is activated and the server has access to the key
- PB-45496 As an administrator I can run a clean-up task to delete metadata private keys entries of soft & hard-deleted users
- PB-45567 As an administrator I can run a passbolt user_index command to list all users
- PB-45567 As an administrator I can run a passbolt user_promote_to_administrator command to promote users to administrators
- PB-45567 As an administrator I can run a passbolt mfa_user_settings_disable command to disable MFA for a given user
- PB-46146 As an administrator I can hide the warning on commands run as non web-user with a configuration flag

### Security
- PB-45158 Adds frame-ancestors:none and form-action:self to the CSP header

### Fixed
- PB-44623 The API should return a 400 instead of 500 on /auth/jwt/logout.json when refresh_token isn't a UUID
- PB-45760 Fixes a translation in setup recover abort email reported by community
- PB-45262 Prevent activity log from showing secret creation during resource share as a secret update

### Maintenance
- PB-45731 As a developer I can ensure by unit tests that all Crowdin translations are parsable
- PB-45788 Updates sessions.sql file as per the latest cakephp skeleton
- PB-43742 Updates PHPUnit vendor to v11
- PB-45829 Upgrades Passbolt API Web Installer to use OpenPGP.js version 6

## [5.7.0-test.1] - 2025-11-11
### Added
- PB-46107 As an administrator I can define the number of past secret revisions persisted in DB
- PB-46109 As an administrator I can block the edition of the secret revisions settings with a configuration flag
- PB-46110 As a logged-in user I can view the past secret revisions of a resource
- PB-45059 As an administrator I can see in the healthcheck if zero knowledge is activated and the server has access to the key
- PB-45496 As an administrator I can run a clean-up task to delete metadata private keys entries of soft & hard-deleted users
- PB-45567 As an administrator I can run a passbolt user_index command to list all users
- PB-45567 As an administrator I can run a passbolt user_promote_to_administrator command to promote users to administrators
- PB-45567 As an administrator I can run a passbolt mfa_user_settings_disable command to disable MFA for a given user
- PB-46146 As an administrator I can hide the warning on commands run as non web-user with a configuration flag

### Security
- PB-45158 Adds frame-ancestors:none and form-action:self to the CSP header

### Fixed
- PB-44623 The API should return a 400 instead of 500 on /auth/jwt/logout.json when refresh_token isn't a UUID
- PB-45760 Fixes a translation in setup recover abort email reported by community
- PB-45262 Prevent activity log from showing secret creation during resource share as a secret update

### Maintenance
- PB-45731 As a developer I can ensure by unit tests that all Crowdin translations are parsable
- PB-45788 Updates sessions.sql file as per the latest cakephp skeleton
- PB-43742 Updates PHPUnit vendor to v11
- PB-45829 Upgrades Passbolt API Web Installer to use OpenPGP.js version 6

## [5.6.1] - 2025-11-04
### Security
- PB-45919 Fix security issue in query generation for CakePHP

## [5.6.1-test.1] - 2025-11-03
### Security
- PB-45919 Fix security issue in query generation for CakePHP

## [5.6.0] - 2025-10-08
### Added
- PB-45058 Add datacheck to check for existing metadata key with no metadata private keys
- PB-44187 As an admin I cannot delete a metadata key associated with a deleted resource
- PB-44183 As a user that is sole owner of v4 resources when v4 resources types are disabled, v4 resources should be ignored on an ownership transfer request
- PB-44770 As a user I want to configure the trusted_proxies list as an environment variable
- PB-45471 Add new database migration to add standalone notes resource type
- PB-45472 Update resource types endpoints tests to assert enable/disable is working for new standalone notes resource type
- PB-45473 Update resources endpoints tests to accommodate new standalone notes resource type

### Fixed
- PB-45222 Fix EmailDigest not working for v5 resources
- PB-45447 Fix PUT /metadata/keys/<uuid>.json endpoint returning 500 error with trailing data
- PB-45436 As an administrator I can define the default cache engine with an environment variable
- PB-45454 Fix 500 error due to MySQL deadlock on create resource endpoint
- PB-45456 Allow editing of v4 resources even when v4 resource type creation is disabled
- PB-45258 Fix grammatical errors in the resource update email content
- PB-45057 Reduce memory consumption on the action logs endpoints
- PB-45057 Reduce memory consumption on resources and folders index endpoints

### Maintenance
- PB-44813 Bring back DDEV ldap related services for development environment
- PB-44593 Bump i18next version
- PB-45161 Fix regularly failing UsersIndexControllerPaginationTest.php test
- PB-45270 Add custom exception message with client IP in /healthcheck/error.json
- PB-45062 Fix user_setup_complete.php template in LU folder instead of AD

## [5.6.0-rc.1] - 2025-10-03
### Added
- PB-45058 Add datacheck to check for existing metadata key with no metadata private keys
- PB-44187 As an admin I cannot delete a metadata key associated with a deleted resource
- PB-44183 As a user that is sole owner of v4 resources when v4 resources types are disabled, v4 resources should be ignored on an ownership transfer request
- PB-44770 As a user I want to configure the trusted_proxies list as an environment variable
- PB-45471 Add new database migration to add standalone notes resource type
- PB-45472 Update resource types endpoints tests to assert enable/disable is working for new standalone notes resource type
- PB-45473 Update resources endpoints tests to accommodate new standalone notes resource type

### Fixed
- PB-45222 Fix EmailDigest not working for v5 resources
- PB-45447 Fix PUT /metadata/keys/<uuid>.json endpoint returning 500 error with trailing data
- PB-45436 As an administrator I can define the default cache engine with an environment variable
- PB-45454 Fix 500 error due to MySQL deadlock on create resource endpoint
- PB-45456 Allow editing of v4 resources even when v4 resource type creation is disabled
- PB-45258 Fix grammatical errors in the resource update email content
- PB-45057 Reduce memory consumption on the action logs endpoints
- PB-45057 Reduce memory consumption on resources and folders index endpoints

### Maintenance
- PB-44813 Bring back DDEV ldap related services for development environment
- PB-44593 Bump i18next version
- PB-45161 Fix regularly failing UsersIndexControllerPaginationTest.php test
- PB-45270 Add custom exception message with client IP in /healthcheck/error.json
- PB-45062 Fix user_setup_complete.php template in LU folder instead of AD

## [5.6.0-test.1] - 2025-10-01
### Added
- PB-45058 Add datacheck to check for existing metadata key with no metadata private keys
- PB-44187 As an admin I cannot delete a metadata key associated with a deleted resource
- PB-44183 As a user that is sole owner of v4 resources when v4 resources types are disabled, v4 resources should be ignored on an ownership transfer request
- PB-44770 As a user I want to configure the trusted_proxies list as an environment variable
- PB-45471 Add new database migration to add standalone notes resource type
- PB-45472 Update resource types endpoints tests to assert enable/disable is working for new standalone notes resource type
- PB-45473 Update resources endpoints tests to accommodate new standalone notes resource type

### Fixed
- PB-45222 Fix EmailDigest not working for v5 resources
- PB-45447 Fix PUT /metadata/keys/<uuid>.json endpoint returning 500 error with trailing data
- PB-45436 As an administrator I can define the default cache engine with an environment variable
- PB-45454 Fix 500 error due to MySQL deadlock on create resource endpoint
- PB-45456 Allow editing of v4 resources even when v4 resource type creation is disabled
- PB-45258 Fix grammatical errors in the resource update email content
- PB-45057 Reduce memory consumption on the action logs endpoints
- PB-45057 Reduce memory consumption on resources and folders index endpoints

### Maintenance
- PB-44813 Bring back DDEV ldap related services for development environment
- PB-44593 Bump i18next version
- PB-45161 Fix regularly failing UsersIndexControllerPaginationTest.php test
- PB-45270 Add custom exception message with client IP in /healthcheck/error.json
- PB-45062 Fix user_setup_complete.php template in LU folder instead of AD

## [5.5.2] - 2025-09-29
### Fixed
- PB-45439 As an administrator I can edit the metadata key settings when not editing zero-knowledge mode

## [5.5.2-test.1] - 2025-09-29
### Fixed
- PB-45439 As an administrator I can edit the metadata key settings when not editing zero-knowledge mode  when not editing zero-knowledge mode

## [5.5.0] - 2025-09-15
### Added
- PB-44639 As an administrator, when updating metadata settings from friendly mode to zero knowledge, I should see the server key dropped in DB
- PB-44756 Updates metadata keys settings endpoint to accept server metadata private key
- PB-44752 Adds a new data check for existing resources v5 encrypted with hard or soft deleted shared metadata key

### Fixed
- PB-45060 Fixes custom fields json schema properties type
- PB-45062 Fixes user_setup_complete.php template in LU folder instead of AD
- PB-44760 Fixes health check "record not found in table organization_settings" issue (GITHUB #563)

### Maintenance
- PB-44915 Changes DDEV containers names and URLs from passbolt-ce-api to passbolt-api
- PB-44813 Updates ddev config
- PB-44772 Speeds up continuous integration by splitting pipelines in two distinct test suites

## [5.5.0-rc.1] - 2025-09-12
### Added
- PB-44639 As an administrator, when updating metadata settings from friendly mode to zero knowledge, I should see the server key dropped in DB
- PB-44756 Updates metadata keys settings endpoint to accept server metadata private key
- PB-44752 Adds a new data check for existing resources v5 encrypted with hard or soft deleted shared metadata key

### Fixed
- PB-45060 Fixes custom fields json schema properties type
- PB-45062 Fixes user_setup_complete.php template in LU folder instead of AD
- PB-44760 Fixes health check "record not found in table organization_settings" issue (GITHUB #563)

### Maintenance
- PB-44915 Changes DDEV containers names and URLs from passbolt-ce-api to passbolt-api
- PB-44813 Updates ddev config
- PB-44772 Speeds up continuous integration by splitting pipelines in two distinct test suites

## [5.5.0-test.2] - 2025-09-08
### Added
- PB-44639 As an administrator, when updating metadata settings from friendly mode to zero knowledge, I should see the server key dropped in DB
- PB-44756 Updates metadata keys settings endpoint to accept server metadata private key
- PB-44752 Adds a new data check for existing resources v5 encrypted with hard or soft deleted shared metadata key

### Fixed
- PB-45060 Fixes custom fields json schema properties type
- PB-45062 Fixes user_setup_complete.php template in LU folder instead of AD
- PB-44760 Fixes health check "record not found in table organization_settings" issue (GITHUB #563)

### Maintenance
- PB-44915 Changes DDEV containers names and URLs from passbolt-ce-api to passbolt-api
- PB-44813 Updates ddev config
- PB-44772 Speeds up continuous integration by splitting pipelines in two distinct test suites

## [5.5.0-test.1] - 2025-09-02
### Added
- PB-44639 As an administrator when updating metadata settings from friendly mode to zero knowledge I should see the server key dropped in DB
- PB-44756 Update metadata keys settings endpoint to accept server metadata private key
- PB-44752 Add new datacheck for existing resources v5 encrypted with hard or soft deleted shared metadata key

### Fixed
- PB-45060 Fixes custom fields json schema properties type
- PB-45062 Fix user_setup_complete.php template in LU folder instead of AD
- PB-44760 Fix healthcheck "record not found in table organization_settings" issue (GITHUB #563)

### Maintenance
- PB-44915 Change DDEV containers names and URLs from passbolt-ce-api to passbolt-api
- PB-44813 Update ddev config

## [5.4.1] - 2025-08-13
### Fixed
- PB-44220 Enforces the format to datetime string when persisting the last_logged_in field on users login

## [5.4.0] - 2025-08-12
### Added
- PB-43713 Translate the application in Czech
- PB-44285 Add endpoint to help clients enable E2EE by default for new instances
- PB-44184 As an administrator I should not be allowed to retrieve resources to migrate from v4 to v5 resource types from v4 resource types that are deleted
- PB-44071 Add a cleanup tasks to soft-delete inactive users with same usernames
- PB-44376 Set ECC key type as a default for new users
- PB-44405 Add new healthcheck to notify administrators when there are no active metadata key if E2EE is enabled
- PB-44406 Add new healthcheck to notify administrators when zero-knowledge disabled and the server does not have access to the shared metadata key
- PB-44407 Add new healthcheck to notify administrators when server cannot validate its own shared metadata private key
- PB-44416 Add metadata settings getting started endpoint
- PB-38155 Add JSON schema definition to resource types migrations
- PB-44474 Switch encrypted metadata plugin to stable
- PB-43631 As an admin running a command as root, I should see the name of the command in the suggestion proposed by the CLI

### Fixed
- PB-43187 Retrieve user last logged data from users table instead of the log to improve application performance
- PB-43922 Fix notification emails about a resource update
- PB-43709 Fix enabling E2EE without a key should trigger an error
- PB-44093 Fix a warning message in ActionLogsUsernameQueryStrategy
- PB-44177 Fix as a user I should not be allowed to create v4 resource if the resource type is deleted
- PB-44179 Fix as user I should not view/index v4 resource types if the resource type is deleted
- PB-43936 Fix IsValidEncryptedMetadataPrivateKey should log, then return false and not throw an exception if isMessageForRecipient fails
- PB-44182 Fix as user I should not be allowed to delete a v4 resource if v4 resource type is deleted
- PB-44181 Fix as user I should not be allowed to share a v4 resource if v4 resource type is deleted
- PB-44252 Fix as an admin I should not be able to set the role of a user to guest
- PB-44178 Fix as a user I should not be allowed to update v4 resource if the resource type is deleted
- PB-44180 Fix as user I should not view/index v5 resource types if the resource type is deleted
- PB-44186 Fix as an administrator I should not be able to rotate the metadata key for resources that have a deleted resource types
- PB-44189 Fix command line metadata commands should be loaded in debug mode only
- PB-43936 Fix isMessageForRecipient should work if encryption is done with main key
- PB-41818 Fix as a user setting a date as boolean the API should not return a 500 code response

### Maintenance
- PB-43524 Create a TestData plugin in plugins/PassboltCe
- PB-44087 Remove V331 backward compatibility migration
- PB-44267 Bump SeleniumApi plugin version
- PB-43752 Add assertJson assertions to folders endpoints
- PB-41818 Bump cakephp version to 5.2.6

## [5.4.0-rc.1] - 2025-08-11
### Added
- PB-43713 Translate the application in Czech
- PB-44285 Add endpoint to help clients enable E2EE by default for new instances
- PB-44184 As an administrator I should not be allowed to retrieve resources to migrate from v4 to v5 resource types from v4 resource types that are deleted
- PB-44071 Add a cleanup tasks to soft-delete inactive users with same usernames
- PB-44376 Set ECC key type as a default for new users
- PB-44405 Add new healthcheck to notify administrators when there are no active metadata key if E2EE is enabled
- PB-44406 Add new healthcheck to notify administrators when zero-knowledge disabled and the server does not have access to the shared metadata key
- PB-44407 Add new healthcheck to notify administrators when server cannot validate its own shared metadata private key
- PB-44416 Add metadata settings getting started endpoint
- PB-38155 Add JSON schema definition to resource types migrations
- PB-44474 Switch encrypted metadata plugin to stable
- PB-43631 As an admin running a command as root, I should see the name of the command in the suggestion proposed by the CLI

### Fixed
- PB-43187 Retrieve user last logged data from users table instead of the log to improve application performance
- PB-43922 Fix notification emails about a resource update
- PB-43709 Fix enabling E2EE without a key should trigger an error
- PB-44093 Fix a warning message in ActionLogsUsernameQueryStrategy
- PB-44177 Fix as a user I should not be allowed to create v4 resource if the resource type is deleted
- PB-44179 Fix as user I should not view/index v4 resource types if the resource type is deleted
- PB-43936 Fix IsValidEncryptedMetadataPrivateKey should log, then return false and not throw an exception if isMessageForRecipient fails
- PB-44182 Fix as user I should not be allowed to delete a v4 resource if v4 resource type is deleted
- PB-44181 Fix as user I should not be allowed to share a v4 resource if v4 resource type is deleted
- PB-44252 Fix as an admin I should not be able to set the role of a user to guest
- PB-44178 Fix as a user I should not be allowed to update v4 resource if the resource type is deleted
- PB-44180 Fix as user I should not view/index v5 resource types if the resource type is deleted
- PB-44186 Fix as an administrator I should not be able to rotate the metadata key for resources that have a deleted resource types
- PB-44189 Fix command line metadata commands should be loaded in debug mode only
- PB-43936 Fix isMessageForRecipient should work if encryption is done with main key
- PB-41818 Fix as a user setting a date as boolean the API should not return a 500 code response

### Maintenance
- PB-43524 Create a TestData plugin in plugins/PassboltCe
- PB-44087 Remove V331 backward compatibility migration
- PB-44267 Bump SeleniumApi plugin version
- PB-43752 Add assertJson assertions to folders endpoints
- PB-41818 Bump cakephp version to 5.2.6

## [5.4.0-test.3] - 2025-08-08
### Fixed
- PB-44573 Ensure standalone custom fields is resource type is updated irrespective of instance being installed for the first time with v5.3.0 or v5.3.1

## [5.4.0-test.2] - 2025-08-07
### Fixed
- PB-44578 Align metadata setup settings entry point variable name with client

## [5.4.0-test.1] - 2025-08-07
### Added
- PB-43713 Translate the application in Czech
- PB-44285 Add endpoint to help clients enable E2EE by default for new instances
- PB-44184 As an administrator I should not be allowed to retrieve resources to migrate from v4 to v5 resource types from v4 resource types that are deleted
- PB-44071 Add a cleanup tasks to soft-delete inactive users with same usernames
- PB-44376 Set ECC key type as a default for new users
- PB-44405 Add new healthcheck to notify administrators when there are no active metadata key if E2EE is enabled
- PB-44406 Add new healthcheck to notify administrators when zero-knowledge disabled and the server does not have access to the shared metadata key
- PB-44407 Add new healthcheck to notify administrators when server cannot validate its own shared metadata private key
- PB-44416 Add metadata settings getting started endpoint
- PB-38155 Add JSON schema definition to resource types migrations
- PB-44474 Switch encrypted metadata plugin to stable
- PB-43631 As an admin running a command as root, I should see the name of the command in the suggestion proposed by the CLI

### Fixed
- PB-43187 Retrieve user last logged data from users table instead of the log to improve application performance
- PB-43922 Fix notification emails about a resource update
- PB-43709 Fix enabling E2EE without a key should trigger an error
- PB-44093 Fix a warning message in ActionLogsUsernameQueryStrategy
- PB-44177 Fix as a user I should not be allowed to create v4 resource if the resource type is deleted
- PB-44179 Fix as user I should not view/index v4 resource types if the resource type is deleted
- PB-43936 Fix IsValidEncryptedMetadataPrivateKey should log, then return false and not throw an exception if isMessageForRecipient fails
- PB-44182 Fix as user I should not be allowed to delete a v4 resource if v4 resource type is deleted
- PB-44181 Fix as user I should not be allowed to share a v4 resource if v4 resource type is deleted
- PB-44252 Fix as an admin I should not be able to set the role of a user to guest
- PB-44178 Fix as a user I should not be allowed to update v4 resource if the resource type is deleted
- PB-44180 Fix as user I should not view/index v5 resource types if the resource type is deleted
- PB-44186 Fix as an administrator I should not be able to rotate the metadata key for resources that have a deleted resource types
- PB-44189 Fix command line metadata commands should be loaded in debug mode only
- PB-43936 Fix isMessageForRecipient should work if encryption is done with main key
- PB-41818 Fix as a user setting a date as boolean the API should not return a 500 code response

### Maintenance
- PB-43524 Create a TestData plugin in plugins/PassboltCe
- PB-44087 Remove V331 backward compatibility migration
- PB-44267 Bump SeleniumApi plugin version
- PB-43752 Add assertJson assertions to folders endpoints
- PB-41818 Bump cakephp version to 5.2.6

## [5.3.2] - 2025-07-16
### Fixed
- PB-43910 As an administrator installing passbolt on postgres, the default postgres schema should be public
- PB-43956 Fix OpenPGP_PHP behavior discrepancy for keys with multiple self-signed key signatures with different expiry times
- PB-43746 A metadata key should be shareable with new users even if the administrator who created the key is soft-deleted
- PB-37106 As an administrator running healthCheck, I should see the right path to the logs if the directory permissions are not correct

### Maintenance
- PB-43966 Selenium specific endpoints should be enabled for local testing with ddev
- PB-43480 Writes stack traces in logs on metadata key validation 500 errors

## [5.3.2-test.1] - 2025-07-15
### Fixed
- PB-43910 As an administrator installing passbolt on postgres, the default postgres schema should be public
- PB-43956 Fix OpenPGP_PHP behavior discrepancy for keys with multiple self-signed key signatures with different expiry times
- PB-43746 A metadata key should be shareable with new users even if the administrator who created the key is soft-deleted
- PB-37106 As an administrator running healthCheck, I should see the right path to the logs if the directory permissions are not correct

### Maintenance
- PB-43966 Selenium specific endpoints should be enabled for local testing with ddev
- PB-43480 Writes stack traces in logs on metadata key validation 500 errors

## [5.3.1] - 2025-07-09
### Fixed
- PB-43748 Users are unable to save a new standalone custom field resource

## [5.3.0] - 2025-07-09
### Added
- PB-43066 As a developer I can setup my development environment using ddev
- PB-43188 Adds Custom Fields Standalone resource type migration
- PB-43383 Improves the performance of most paginated endpoints
- PB-43659 Improve error handling when metadata key could not be saved due to unexpected reason

### Fixed
- PB-43382 As an administrator I should not get a connection error when running the healthcheck when the database is empty
- PB-43007 Fixes emails not sent after v5 upgrade if SMTP credentials are stored in environment variables (GITHUB #545)
- PB-43122 As an administrator retrieving users metadata key, I should not trigger a validation type issue on the missing metadata_keys_id in certain conditions
- PB-43137 Fixes a potential settings conflict in user key policy where key of type rsa should not have a preferred curve
- PB-42784 As an administrator I should not get a health check error when all email notifications are enabled
- PB-43259 Fixes a record not found error on table `organization_settings` in healthcheck after v5 upgrade (GITHUB #548)
- PB-42072 As a user sharing permissions, I should not get a 500 response if the payload is wrongly formatted
- PB-42071 Fixes 500 errors on malformed UTF-8 character-based URLs when using the JsonTraceFormatter class
- PB-43659 Improves the error catching on creation of organization metadata key

### Maintenance
- PB-42177 Upgrades CakePHP version to 5.2.5
- PB-43010 Replaces the use of static fixtures with fixture factories in multiple test cases
- PB-41724 Map _cake_core_ cache config with _cake_translations_ in the bootstrap.php file
- PB-42380 Adds the missing v5 resource types on data insertion in the passbolt-test-data vendor
- PB-43658 Use FQN to load all vendor plugin

## [5.3.0-rc.1] - 2025-07-04
### Added
- PB-43066 As a developer I can setup my development environment using dev
- PB-43188 Add Custom Fields Standalone resource type migration

### Improved
- PB-43383 Improves the performance of most paginated endpoints
- PB-43659 Improve error handling when metadata key could not be saved due to unexpected reason

### Fixed
- PB-43382 As an administrator I should not get a connection error when running the healthcheck when the database is empty
- PB-43007 Fixes emails not sent after v5 upgrade if SMTP credentials are stored in environment variables (GITHUB #545)
- PB-43122 As an administrator retrieving users metadata key, I should not trigger a validation type issue on the missing metadata_keys_id in certain conditions
- PB-43137 Fixes a potential settings conflict in user key policy where key of type rsa should not have a preferred curve
- PB-42784 As an administrator I should not get a health check error when all email notifications are enabled
- PB-43259 Fixes a record not found error on table `organization_settings` in healthcheck after v5 upgrade (GITHUB #548)
- PB-42072 As a user sharing permissions, I should not get a 500 response if the payload is wrongly formatted
- PB-42071 Fixes 500 errors on malformed UTF-8 character-based URLs when using the JsonTraceFormatter class

### Maintenance
- PB-42177 Upgrades CakePHP version to 5.2.5
- PB-43010 Replaces the use of static fixtures with fixture factories in multiple test cases
- PB-41724 Map _cake_core_ cache config with _cake_translations_ in the bootstrap.php file
- PB-42380 Adds the missing v5 resource types on data insertion in the passbolt-test-data vendor

## [5.3.0-test.1] - 2025-07-02
### Added
- PB-43066 As a developer I can setup my development environment using ddev

### Improved
- PB-43383 Improves the performance of most paginated endpoints

### Fixed
- PB-43382 As an administrator I should not get a connection error when running the healthcheck when the database is empty
- PB-43007 Fixes emails not sent after v5 upgrade if SMTP credentials are stored in environment variables (GITHUB #545)
- PB-43122 As an administrator retrieving users metadata key, I should not trigger a validation type issue on the missing metadata_keys_id in certain conditions
- PB-43137 Fixes a potential settings conflict in user key policy where key of type rsa should not have a preferred curve
- PB-42784 As an administrator I should not get a health check error when all email notifications are enabled
- PB-43259 Fixes a record not found error on table `organization_settings` in healthcheck after v5 upgrade (GITHUB #548)
- PB-42072 As a user sharing permissions, I should not get a 500 response if the payload is wrongly formatted
- PB-42071 Fixes 500 errors on malformed UTF-8 character-based URLs when using the JsonTraceFormatter class

### Maintenance
- PB-42177 Upgrade CakePHP version to 5.2.5
- PB-43010 Replaces the use of static fixtures with fixture factories in multiple test case
- PB-41724 Map _cake_core_ cache config with _cake_translations_ in the bootstrap.php file
- PB-42380 Adds the missing v5 resource types on data insertion in the passbolt-test-data vendor

## [5.2.0] - 2025-06-11
### Added
- PB-42861 As a user I can use passbolt in Slovenian language
- PB-42986 As a user I can use passbolt in Ukrainian language
- PB-42878 Add User GPG key policies (ECC by default) support behind a feature flag
- PB-41966 As a resource owner I should receive a notification on the day that my resources expire

### Improved
- PB-42706 Alias POST /metadata/keys/privates.json endpoint to POST /metadata/keys/private.json

### Fixed
- PB-42800 The check metadata key presence in the healthcheck should not fail if no metadata key is required
- PB-42701 Fixes the contain of missing metadata key on view user endpoint
- PB-42592 Add missing attribute in ldap default configuration file
- PB-42574 Fix LDAP Typed property error

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured
- PB-42379 PBL-13-004 - Fixes HTML injections in email notifications

### Maintenance
- PB-42935 Upgrade API babel dev dependency
- PB-42893 Upgrade API lock-link-api dev dependency
- PB-42923 refactor code to remove warning in selenium execution context

## [5.2.0-rc.1] - 2025-06-04
### Added
- PB-42861 As a user I can use passbolt in Slovenian language
- PB-42986 As a user I can use passbolt in Ukrainian language
- PB-42878 Add User GPG key policies (ECC by default) support behind a feature flag
- PB-41966 As a resource owner I should receive a notification on the day that my resources expire

### Improved
- PB-42706 Alias POST /metadata/keys/privates.json endpoint to POST /metadata/keys/private.json

### Fixed
- PB-42800 The check metadata key presence in the healthcheck should not fail if no metadata key is required
- PB-42701 Fixes the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured
- PB-42378 PBL-13-001 - Fixes open redirect on MFA step in login
- PB-42379 PBL-13-004 - Fixes HTML injections in email notifications
- PB-43115 Fix XSS in email footer where the full base URL was not escaped or sanitized

### Maintenance
- PB-42935 Upgrade API babel dev dependency
- PB-42893 Upgrade API lock-link-api dev dependency
- PB-42923 refactor code to remove warning in selenium execution context

## [5.2.0-test.1] - 2025-06-03
### Added
- PB-42861 As a user I can use passbolt in Slovenian language
- PB-42986 As a user I can use passbolt in Ukrainian language
- PB-42878 Add User GPG key policies (ECC by default) support behind a feature flag
- PB-41966 As a resource owner I should receive a notification on the day that my resources expire

### Improved
- PB-42706 Alias POST /metadata/keys/privates.json endpoint to POST /metadata/keys/private.json

### Fixed
- PB-42800 The check metadata key presence in the healthcheck should not fail if no metadata key is required
- PB-42701 Fixes the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured
- PB-42378 PBL-13-001 - Fixes open redirect on MFA step in login
- PB-42379 PBL-13-004 - Fixes HTML injections in email notifications

### Maintenance
- PB-42935 Upgrade API babel dev dependency
- PB-42893 Upgrade API lock-link-api dev dependency
- PB-42923 refactor code to remove warning in selenium execution context

## [5.1.1] - 2025-05-22
### Fixed
- PB-42701 Fix the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured

## [5.1.1-test.1] - 2025-05-21
### Fixed
- PB-42701 Fix the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured

## [5.1.0] - 2025-05-15
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 Enable the metadata plugin by default

### Fixed
- PB-41736 Adjust the datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix the APP_BASE variable ignored when generated URLs with CakePHP

### Security
- PB-42378 PBL-13-001 - Sanitize open redirect on MFA step in login

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json
- PB-42117 Populate metadata key ID for personal resources if null in payload

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar

## [5.1.0-rc.1] - 2025-05-12
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 Enable the metadata plugin by default

### Fixed
- PB-41736 Adjust the datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix the APP_BASE variable ignored when generated URLs with CakePHP

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json
- PB-42117 Populate metadata key ID for personal resources if null in payload

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar

## [5.1.0-test.1] - 2025-05-07
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 The metadata plugin is enabled by default

### Fixed
- PB-41736 Adjust datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix APP_BASE not included in generated URLs

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json
- PB-42117 Populate metadata key ID for personal resources if null in payload

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar

## [5.0.0] - 2025-04-10
### Added
- PB-39434 As an administrator I can log user actions on file in an SIEM compatible format
- PB-39627 Enforce PHP 8.2 as minimum passbolt API requirement
- PB-40155 Add Passbolt API support of PHP 8.4
- PB-40247 Add API status documentation link to the health check command

### Fixed
- PB-39706 When creating a user from CLI the metadata_private_keys should have their fields created_by and modified_by set
- PB-41356 As an administrator I can delete a resource type associated to deleted resources
- PB-41374 Fix unlimited session lifetime introduced in CakePHP 5
- PB-41379 Updates the minimum next version to 8.2 to remove false warning from installation

### Maintenance
- PB-28246 Refactor the whole application to upgrade CakePHP to version 5
- PB-39434 Add code coverage to ActionLogsUsernameQueryStrategy
- PB-39660 Mock MfaFormInterface to avoid tests failing occasionally
- PB-39630 Fix ResourcesIndexControllerPaginationTest recurrently failing test

## [5.0.0-rc.2] - 2025-04-08
### Fixed
- PB-41374 Fix unlimited session lifetime introduced in CakePHP 5
- PB-41379 Updates the minimum next version to 8.2 to remove false warning from installation

## [5.0.0-rc.1] - 2025-04-07
### Added
- PB-39434 As an administrator I can log user actions on file in an SIEM compatible format
- PB-39627 Enforce PHP 8.2 as minimum passbolt API requirement
- PB-40155 Add Passbolt API support of PHP 8.4
- PB-40247 Add API status documentation link to the health check command

### Fixed
- PB-39706 When creating a user from CLI the metadata_private_keys should have their fields created_by and modified_by set
- PB-41356 As an administrator I can delete a resource type associated to deleted resources

### Maintenance
- PB-28246 Refactor the whole application to upgrade CakePHP to version 5
- PB-39434 Add code coverage to ActionLogsUsernameQueryStrategy
- PB-39660 Mock MfaFormInterface to avoid tests failing occasionally
- PB-39630 Fix ResourcesIndexControllerPaginationTest recurrently failing test

## [5.0.0-test.4] - 2025-04-02
### Added
- PB-39434 As an administrator I can log user actions on file in an SIEM compatible format
- PB-40155 Add Passbolt API support of PHP 8.4
- PB-39627 Enforce PHP 8.2 as minimum passbolt API requirement

### Fixed
- PB-39706 When creating a user from CLI the metadata_private_keys should have their fields created_by and modified_by set

### Improved
- PB-38164 Migrate passbolt API skeleton to v5
- PB-40247 Add API status documentation link to the health check command

### Maintenance
- PB-28246 Refactor the whole application to upgrade CakePHP to version 5
- PB-39434 Add code coverage to ActionLogsUsernameQueryStrategy
- PB-39660 Mock MfaFormInterface to avoid tests failing occasionally
- PB-39630 Fix ResourcesIndexControllerPaginationTest recurrently failing test

## [5.0.0-test.2] - 2025-03-28
### Maintenance
- PB-39712 Update CakePHP to version 5

### Fixed
- PB-40668 Cannot assign bool to property App\Notification\Email\EmailSender::$appFullBaseUrl of type string error

## [5.0.0-test.1] - 2025-03-28
### Maintenance
- PB-39712 Update CakePHP to version 5

## [4.12.1-test.1] - 2025-03-14
### Fixed
- PB-39965 Fixes an issue when fetching the locale of disabled users when queried in a sub-query

## [4.12.0] - 2025-03-12
### Added
- PB-39395 As an administrator I can contain permissions when upgrading folders to v5 format
- PB-39394 As an administrator I can contain permissions when upgrading resources to v5 format
- PB-38850 As an administrator I cannot rotate entities while two metadata keys are active
- PB-37699 As an administrator I can upgrade folders to v5 format
- PB-37363 As an administrator I can rotate metadata keys encrypting folders metadata
- PB-36582 As an administrator I cannot reuse a previously deleted metadata key

### Fixed
- PB-39512 Fix during metadata upgrade process, the resource_type_id field is now updated in the database
- PB-39399 Adds missing fields to metadata private keys in index response
- PB-39393 Fix limit value is null in pagination header response for rotate & upgrade endpoints
- PB-38770 Fix email subject for delete resource email when resource is v5
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format

### Maintenance
- PB-39629 Set next minimum PHP version to 8.2 as passbolt v5 will not support lower PHP versions

## [4.12.0-rc.1] - 2025-03-06
### Added
- PB-39395 As an administrator I can contain permissions when upgrading folders to v5 format
- PB-39394 As an administrator I can contain permissions when upgrading resources to v5 format
- PB-38850 As an administrator I cannot rotate entities while two metadata keys are active
- PB-37699 As an administrator I can upgrade folders to v5 format
- PB-37363 As an administrator I can rotate metadata keys encrypting folders metadata
- PB-36582 As an administrator I cannot reuse a previously deleted metadata key

### Fixed
- PB-39512 Fix during metadata upgrade process, the resource_type_id field is now updated in the database
- PB-39399 Adds missing fields to metadata private keys in index response
- PB-39393 Fix limit value is null in pagination header response for rotate & upgrade endpoints
- PB-38770 Fix email subject for delete resource email when resource is v
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format

### Maintenance
- PB-39629 Set next minimum PHP version to 8.2 as passbolt v5 will not support lower PHP versions

## [4.12.0-test.1] - 2025-03-05
### Added
- PB-39395 As an administrator I can contain permissions when upgrading folders to v5 format
- PB-39394 As an administrator I can contain permissions when upgrading resources to v5 format
- PB-38850 As an administrator I cannot rotate entities while two metadata keys are active
- PB-37699 As an administrator I can upgrade folders to v5 format
- PB-37363 As an administrator I can rotate metadata keys encrypting folders metadata
- PB-36582 As an administrator I cannot reuse a previously deleted metadata key

### Fixed
- PB-39512 Fix during metadata upgrade process, the resource_type_id field is now updated in the database
- PB-39399 Adds missing fields to metadata private keys in index response
- PB-39393 Fix limit value is null in pagination header response for rotate & upgrade endpoints
- PB-38770 Fix email subject for delete resource email when resource is v
- PB-38791 Fix 500 error on the duo MFA setup & verify page when duo service is unavailable
- PB-38771 Fix unable to expire the metadata key due to expired datetime format

## [4.11.1] - 2025-02-17
### Security
- PB-39045 Fix empty fullBaseUrl leading to Host header injection attack

## [4.11.1-test.1] - 2025-02-14
### Security
- PB-39045 Fix empty fullBaseUrl leading to Host header injection attack

## [4.11.0] - 2025-01-30
### Added
- PB-35761 As an administrator I receive an email if zero_knowledge_key_share is set to true and a new user completed the setup
- PB-36558 As an administrator I can mark metadata_keys as expired
- PB-35986 As an administrator I can share missing metadata private keys for users that needs them
- PB-35925 As an administrator I can see if users are missing access to metadata keys
- PB-37069 As an administration I can run a command to share metadata private keys with users that need them
- PB-37068 As a user I can see if I am missing metadata keys
- PB-36600 As an administrator I should be notified when an administrator expires a metadata key
- PB-35418 As an administrator I should receive an email notification when a metadata key is deleted
- PB-37361 As an administrator I can rotate metadata keys encrypting resources metadata
- PB-37697 As an administrator I can upgrade resources to v5 format
- PB-35927 As an administrator I can define an allow_v4_v5_upgrade metadata type settings
- PB-35923 As an administrator I cannot add a new metadata key if there is only 2 that are active
- PB-34463 As an administrator I cannot reuse metadata keys as the account recovery key
- PB-35929 Update edit resource to support allow_v4_v5_upgrade settings
- PB-35932 Update edit folders to support allow_v4_v5_upgrade settings

### Fixed
- PB-37719 Fix resource types index controller should not return deleted resource types per default
- PB-36925 Cast configure usage to avoid fatal type error on missing fullBaseUrl
- PB-36576 Fix as a user I cannot create or edit a tag with an expired or deleted metadata key
- PB-37097 Fix prevent to use v5 resource_type_ids if v5 flag is off
- PB-36930 Fix some email sentences not translated and markers errors in translation
- PB-37096 Fix healthcheck relying on symfony/process should fail gracefully in case of process run exception (GITHUB #531)
- PB-36989 Fix namespace composer warnings
- PB-37343 Fixes postgres dump by adding PGPASSWORD env since .pgpass is not generated on the passbolt installation
- PB-38026 As an administrator running the cleanup command I should not see issues on soft deleted groups
- PB-38261 Fix always failing IsNotAccountRecoveryFingerprintRule for metadata keys
- PB-38262 Fix always failing metadata key creation when zero-knowledge is disabled, and no metadata keys are present

### Security
- PB-37974 Upgrade CakePHP to v4.5.9
- PB-38166 Passbolt app router should not fall back on Host header if full-base url is not set

### Maintenance
- PB-35785 Upgrade psalm/phpstan to latest version as applicable
- PB-35119 Fix tests failing when full base url is not-https
- PB-37000 Fix bug of wrong relation for Rbacs to Log.Actions.
- PB-37072 Fix LatestVersionApplicationHealthcheck test failing due to github not reachable
- PB-37071 Fix PHPUnit 10 deprecations
- PB-36237 Fix frequently failing TOTP setup/verify tests
- PB-38184 Fix synk vulnerability for nesbot/carbon PHP Remote File Inclusion

## [4.11.0-test.3] - 2025-01-30
### Added
- PB-35761 As an administrator I receive an email if zero_knowledge_key_share is set to true and a new user completed the setup
- PB-36558 As an administrator I can mark metadata_keys as expired
- PB-35986 As an administrator I can share missing metadata private keys for users that needs them
- PB-35925 As an administrator I can see if users are missing access to metadata keys
- PB-37069 As an administration I can run a command to share metadata private keys with users that need them
- PB-37068 As a user I can see if I am missing metadata keys
- PB-36600 As an administrator I should be notified when an administrator expires a metadata key
- PB-35418 As an administrator I should receive an email notification when a metadata key is deleted
- PB-37361 As an administrator I can rotate metadata keys encrypting resources metadata
- PB-37697 As an administrator I can upgrade resources to v5 format
- PB-35927 As an administrator I can define an allow_v4_v5_upgrade metadata type settings
- PB-35923 As an administrator I cannot add a new metadata key if there is only 2 that are active
- PB-34463 As an administrator I cannot reuse metadata keys as the account recovery key
- PB-35929 Update edit resource to support allow_v4_v5_upgrade settings
- PB-35932 Update edit folders to support allow_v4_v5_upgrade settings

### Fixed
- PB-37719 Fix resource types index controller should not return deleted resource types per default
- PB-36925 Cast configure usage to avoid fatal type error on missing fullBaseUrl
- PB-36576 Fix as a user I cannot create or edit a tag with an expired or deleted metadata key
- PB-37097 Fix prevent to use v5 resource_type_ids if v5 flag is off
- PB-36930 Fix some email sentences not translated and markers errors in translation
- PB-37096 Fix healthcheck relying on symfony/process should fail gracefully in case of process run exception
- PB-36989 Fix namespace composer warnings
- PB-37343 Fixes postgres dump by adding PGPASSWORD env since .pgpass is not generated on the passbolt installation
- PB-38026 As an administrator running the cleanup command I should not see issues on soft deleted groups
- PB-38261 Fix always failing IsNotAccountRecoveryFingerprintRule for metadata keys
- PB-38262 Fix always failing metadata key creation when zero-knowledge is disabled, and no metadata keys are present
- PB-38166 Passbolt app router should not fall back on Host header if full-base url is not set

### Security
- PB-37974 Upgrade CakePHP to v4.5.9

### Maintenance
- PB-35785 Upgrade psalm/phpstan to latest version as applicable
- PB-35119 Fix tests failing when full base url is not-https
- PB-37000 Fix bug of wrong relation for Rbacs to Log.Actions.
- PB-37072 Fix LatestVersionApplicationHealthcheck test failing due to github not reachable
- PB-37071 Fix PHPUnit 10 deprecations
- PB-36237 Fix frequently failing TOTP setup/verify tests
- PB-38184 Fix synk vulnerability for nesbot/carbon PHP Remote File Inclusion

## [4.11.0-test.2] - 2025-01-29
### Added
- PB-35761 As an administrator I receive an email if zero_knowledge_key_share is set to true and a new user completed the setup
- PB-36558 As an administrator I can mark metadata_keys as expired
- PB-35986 As an administrator I can share missing metadata private keys for users that needs them
- PB-35925 As an administrator I can see if users are missing access to metadata keys
- PB-37069 As an administration I can run a command to share metadata private keys with users that need them
- PB-37068 As a user I can see if I am missing metadata keys
- PB-36600 As an administrator I should be notified when an administrator expires a metadata key
- PB-35418 As an administrator I should receive an email notification when a metadata key is deleted
- PB-37361 As an administrator I can rotate metadata keys encrypting resources metadata
- PB-37697 As an administrator I can upgrade resources to v5 format
- PB-35927 As an administrator I can define an allow_v4_v5_upgrade metadata type settings
- PB-35923 As an administrator I cannot add a new metadata key if there is only 2 that are active
- PB-34463 As an administrator I cannot reuse metadata keys as the account recovery key
- PB-35929 Update edit resource to support allow_v4_v5_upgrade settings
- PB-35932 Update edit folders to support allow_v4_v5_upgrade settings

### Fixed
- PB-37719 Fix resource types index controller should not return deleted resource types per default
- PB-36925 Cast configure usage to avoid fatal type error on missing fullBaseUrl
- PB-36576 Fix as a user I cannot create or edit a tag with an expired or deleted metadata key
- PB-37097 Fix prevent to use v5 resource_type_ids if v5 flag is off
- PB-36930 Fix some email sentences not translated and markers errors in translation
- PB-37096 Fix healthcheck relying on symfony/process should fail gracefully in case of process run exception
- PB-36989 Fix namespace composer warnings
- PB-37343 Fixes postgres dump by adding PGPASSWORD env since .pgpass is not generated on the passbolt installation
- PB-38026 As an administrator running the cleanup command I should not see issues on soft deleted groups
- PB-38261 Fix always failing IsNotAccountRecoveryFingerprintRule for metadata keys
- PB-38262 Fix always failing metadata key creation when zero-knowledge is disabled, and no metadata keys are present
- PB-38166 Passbolt app router should not fall back on Host header if full-base url is not set

### Security
- PB-37974 Upgrade CakePHP to v4.5.9

### Maintenance
- PB-35785 Upgrade psalm/phpstan to latest version as applicable
- PB-35119 Fix tests failing when full base url is not-https
- PB-37000 Fix bug of wrong relation for Rbacs to Log.Actions.
- PB-37072 Fix LatestVersionApplicationHealthcheck test failing due to github not reachable
- PB-37071 Fix PHPUnit 10 deprecations
- PB-36237 Fix frequently failing TOTP setup/verify tests
- PB-38184 Fix synk vulnerability for nesbot/carbon PHP Remote File Inclusion

## [4.11.0-test.1] - 2025-01-29
### Added
- PB-35761 As an administrator I receive an email if zero_knowledge_key_share is set to true and a new user completed the setup
- PB-36558 As an administrator I can mark metadata_keys as expired
- PB-35986 As an administrator I can share missing metadata private keys for users that needs them
- PB-35925 As an administrator I can see if users are missing access to metadata keys
- PB-37069 As an administration I can run a command to share metadata private keys with users that need them
- PB-37068 As a user I can see if I am missing metadata keys
- PB-36600 As an administrator I should be notified when an administrator expires a metadata key
- PB-35418 As an administrator I should receive an email notification when a metadata key is deleted
- PB-37361 As an administrator I can rotate metadata keys encrypting resources metadata
- PB-37697 As an administrator I can upgrade resources to v5 format
- PB-35927 As an administrator I can define an allow_v4_v5_upgrade metadata type settings
- PB-35923 As an administrator I cannot add a new metadata key if there is only 2 that are active
- PB-34463 As an administrator I cannot reuse metadata keys as the account recovery key
- PB-35929 Update edit resource to support allow_v4_v5_upgrade settings
- PB-35932 Update edit folders to support allow_v4_v5_upgrade settings

### Fixed
- PB-37719 Fix resource types index controller should not return deleted resource types per default
- PB-36925 Cast configure usage to avoid fatal type error on missing fullBaseUrl
- PB-36576 Fix as a user I cannot create or edit a tag with an expired or deleted metadata key
- PB-37097 Fix prevent to use v5 resource_type_ids if v5 flag is off
- PB-36930 Fix some email sentences not translated and markers errors in translation
- PB-37096 Fix healthcheck relying on symfony/process should fail gracefully in case of process run exception
- PB-36989 Fix namespace composer warnings
- PB-37343 Fixes postgres dump by adding PGPASSWORD env since .pgpass is not generated on the passbolt installation
- PB-38026 As an administrator running the cleanup command I should not see issues on soft deleted groups
- PB-38261 Fix always failing IsNotAccountRecoveryFingerprintRule for metadata keys
- PB-38262 Fix always failing metadata key creation when zero-knowledge is disabled, and no metadata keys are present
- PB-38166 Passbolt app router should not fall back on Host header if full-base url is not set

### Security
- PB-37974 Upgrade CakePHP to v4.5.9

### Maintenance
- PB-35785 Upgrade psalm/phpstan to latest version as applicable
- PB-35119 Fix tests failing when full base url is not-https
- PB-37000 Fix bug of wrong relation for Rbacs to Log.Actions.
- PB-37072 Fix LatestVersionApplicationHealthcheck test failing due to github not reachable
- PB-37071 Fix PHPUnit 10 deprecations
- PB-36237 Fix frequently failing TOTP setup/verify tests
- PB-38184 Fix synk vulnerability for nesbot/carbon PHP Remote File Inclusion

## [4.10.1] - 2024-11-26
### Fixed
- PB-37010 Fix v5 resource types should not be returned if v5 flag is disabled
- PB-37011 Fix session keys creation modified date validation to match ISO 8601 format

## [4.10.1-test.1] - 2024-11-25
### Fixed
- PB-37010 Fix v5 resource types should not be returned if v5 flag is disabled
- PB-37011 Fix session keys creation modified date validation to match ISO 8601 format

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

## [4.10.0-rc.1] - 2024-11-14
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

## [4.10.0-test.1] - 2024-11-12
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

## [4.9.1] - 2024-08-13
### Fixed
- PB-34220 As a user I can search by users and groups case insensitively on PostgreSQL

### Improved
- PB-34246 As an administrator purging the action logs table, I can set a limit option (100k per default)
- PB-34247 Adds a set of actions to be purged by the passbolt action_logs_purge command
- PB-33939 As an administrator when running bin/cake passbolt -h, I should see all the passbolt commands listed

### Maintenance
- PB-32991 Optimizes CI pipeline run time on api repositories
- PB-34219 Adds validation to retention days option in the action_logs_purge command
- PB-33333 Refactor various tests to use fixture factories

## [4.9.1-test.1] - 2024-08-12
### Fixed
- PB-34220 As a user I can search by users and groups case insensitively on PostgreSQL

### Improved
- PB-34246 As an administrator purging the action logs table, I can set a limit option (100k per default)
- PB-34247 Adds a set of actions to be purged by the passbolt action_logs_purge command
- PB-33939 As an administrator when running bin/cake passbolt -h, I should see all the passbolt commands listed

### Maintenance
- PB-32991 Optimizes CI pipeline run time on api repositories
- PB-34219 Adds validation to retention days option in the action_logs_purge command
- PB-33333 Refactor various tests to use fixture factories

## [4.9.0] - 2024-07-23
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array
- PB-33664 Fix missing "is" in the database schema up to date sentence (GITHUB #517)

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package

## [4.9.0-rc.1] - 2024-07-18
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

## [4.9.0-test.2] - 2024-07-17
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

## [4.9.0-test.1] - 2024-07-15
### Added
- PB-33690 Improves response times by adding an index to gpgkeys.user_id column
- PB-33639 Adds additional contain parameters to share/search-aros.json for enhanced performance
- PB-33936 Adds a has-users filter to gpgkeys.json index endpoint
- PB-33813 Adds a fixed limit to the search-aros.json endpoint

### Improved
- PB-33429 As a user I should retrieve resources and folders parent folders in a single query
- PB-33826 Improves the performance of resources.json by improving the datetime fields processing
- PB-24995 Improves last_logged_in property query performance to reduce response time of users.json endpoint
- PB-33653 Improves is_mfa_enabled property query performance to reduce response time of users.json endpoint
- PB-33702 Improves has-access filter performance on users.json
- PB-32591 Validate passbolt.plugins.smtpSettings.security configuration values before passing it to SMTP server
- PB-33214 Update sql export / improve mysql backup command compatibility with mariadb-dump

### Security
- PB-33747 Fix command injections vulnerabilities in composer/composer package

### Fixed
- PB-33616 As a user creating a resource I should get a validation error if the secret is a string and not an array

### Maintenance
- PB-33692 Bump enygma/yubikey to v3.8

## [4.8.0] - 2024-05-21
### Added
- PB-33071 As an administrator I can purge the action logs table with a dedicated command
- PB-33231 As an administrator I want to know if a custom certificate is in use for SMTP
- PB-32579 As an administrator I can view email_queue records via passbolt command

### Improved
- PB-32888 As an admin I should not get a time-out on health checks on air-gapped network
- PB-32983 Access email settings only when emails are sent

### Fixed
- PB-33451 Fix 500 error on authentication when nonce is not a string
- PB-33073 As a user logging in, invalid login operation should not be logged as success in the audit logs
- PB-33234 The application should not throw an error if the JWT public key is not parsable

### Maintenance
- PB-30314 Bump passbolt/passbolt-test-data to v4.8

## [4.8.0-rc.1] - 2024-05-17
### Added
- PB-33071 As an administrator I can purge the action logs table with a dedicated command
- PB-33231 As an administrator I want to know if a custom certificate is in use for SMTP
- PB-32579 As an administrator I can view email_queue records via passbolt command

### Improved
- PB-32888 As an admin I should not get a time-out on health checks on air-gapped network
- PB-32983 Access email settings only when emails are sent

### Fixed
- PB-33451 Fix 500 error on authentication when nonce is not a string
- PB-33073 As a user logging in, invalid login operation should not be logged as success in the audit logs
- PB-33234 The application should not throw an error if the JWT public key is not parsable

### Maintenance
- PB-30314 Bump passbolt/passbolt-test-data to v4.8

## [4.8.0-test.1] - 2024-05-16
### Added
- PB-33071 As an administrator I can purge the action logs table with a dedicated command
- PB-33231 As an administrator I want to know if a custom certificate is in use for SMTP
- PB-32579 As an administrator I can view email_queue records via passbolt command

### Improved
- PB-32888 As an admin I should not get a time-out on health checks on air-gapped network
- PB-32983 Access email settings only when emails are sent

### Fixed
- PB-33451 Fix 500 error on authentication when nonce is not a string
- PB-33073 As a user logging in, invalid login operation should not be logged as success in the audit logs
- PB-33234 The application should not throw an error if the JWT public key is not parsable

### Maintenance
- PB-30314 Bump passbolt/passbolt-test-data to v4.8

## [4.7.0] - 2024-04-30
### Added
- PB-30330 Add HTTP HEAD method support to /healthcheck/status.json to support more uptime monitoring tools (GITHUB #507)
- PB-26156 As an administrator I can configure SMTP to use TLS with a self-signed cert on my mail server (GITHUB #498)

### Security
- PB-30255 As an authenticated user I cannot access to the healthcheck endpoint when debug is on

### Fixed
- PB-30379 As an authenticating user I should not get a 500 if the gpg_auth is not an array
- PB-32889 As an administrator I should not get an exception when running core healthcheck and the host cannot be resolved
- PB-32928 As user I should see the accurate URL in the email footer when passbolt runs on multiple instances
- PB-32566 As a user setting up my account I should not get an unexpected 500
- PB-32903 Fix deprecation error on password expiry settings validation

### Maintenance
- PB-29983 Refactor health check code domain for better maintenance
- PB-30394 Moves code in ActionLogsModelListener into a dedicated service
- PB-32881 Disable by default all plugins in integration tests
- PB-32978 Use dependency proxy to reduce docker pull limit
- PB-22605 Refactor ShareSearchControllerTest, SecretViewControllerTest and GroupsDeleteControllerTest with fixture factories
- PB-32594 Add tests for SecretCreateService

## [4.7.0-rc.1] - 2024-04-26
### Added
- PB-30330 Add HTTP HEAD method support to /healthcheck/status.json to support more uptime monitoring tools (GITHUB #507)
- PB-26156 As an administrator I can configure SMTP to use TLS with a self-signed cert on my mail server (GITHUB #498)

### Security
- PB-30255 As an authenticated user I cannot access to the healthcheck endpoint when debug is on

### Fixed
- PB-30379 As an authenticating user I should not get a 500 if the gpg_auth is not an array
- PB-32889 As an administrator I should not get an exception when running core healthcheck and the host cannot be resolved
- PB-32928 As user I should see the accurate URL in the email footer when passbolt runs on multiple instances
- PB-32566 As a user setting up my account I should not get an unexpected 500
- PB-32903 Fix deprecation error on password expiry settings validation

### Maintenance
- PB-29983 Refactor health check code domain for better maintenance
- PB-30394 Moves code in ActionLogsModelListener into a dedicated service
- PB-32881 Disable by default all plugins in integration tests
- PB-32978 Use dependency proxy to reduce docker pull limit
- PB-22605 Refactor ShareSearchControllerTest, SecretViewControllerTest and GroupsDeleteControllerTest with fixture factories
- PB-32594 Add tests for SecretCreateService

## [4.7.0-test.2] - 2024-04-26
### Fixed
- PB-33084 New release with fixed release pipes

## [4.7.0-test.1] - 2024-04-24
### Added
- PB-30330 Add HTTP HEAD method support to /healthcheck/status.json to support more uptime monitoring tools (GITHUB #507)
- PB-26156 As an administrator I can configure SMTP to use TLS with a self-signed cert on my mail server (GITHUB #498)

### Security
- PB-30255 As an authenticated user I cannot access to the healthcheck endpoint when debug is on

### Fixed
- PB-30379 As an authenticating user I should not get a 500 if the gpg_auth is not an array
- PB-32889 As an administrator I should not get an exception when running core healthcheck and the host cannot be resolved
- PB-32928 As user I should see the accurate URL in the email footer when passbolt runs on multiple instances
- PB-32566 As a user setting up my account I should not get an unexpected 500
- PB-32903 Fix deprecation error on password expiry settings validation

### Maintenance
- PB-29983 Refactor health check code domain for better maintenance
- PB-30394 Moves code in ActionLogsModelListener into a dedicated service
- PB-32881 Disable by default all plugins in integration tests
- PB-32978 Use dependency proxy to reduce docker pull limit
- PB-22605 Refactor ShareSearchControllerTest, SecretViewControllerTest and GroupsDeleteControllerTest with fixture factories
- PB-32594 Add tests for SecretCreateService

## [4.6.2] - 2024-04-11
### Security
- PB-32932 Fix error template title

## [4.6.2-test.2] - 2024-04-11
### Security
- PB-32932 Fix error template title

## [4.6.1] - 2024-03-27
### Fixed
- PB-32354 As an admin, I can re-enable a suspended user (GITHUB #512)

## [4.6.1-test.1] - 2024-03-26
### Fixed
- PB-32354 As an admin, I can re-enable a suspended user (GITHUB #512)

## [4.6.0] - 2024-03-14
### Added
- PB-24485 As an administrator I can view the API healthcheck in the administration section
- PB-29396 As an administrator I can hide the share folder capability with a RBAC
- PB-25463 As an administrator I can disable the healthcheck index endpoint with a flag
- PB-29397 As an administrator I can disable the healthcheck administration panel with a flag

### Improved
- PB-29009 As an administrator completing my setup I should not receive a notification that I completed my setup
- PB-26152 The API should identify openpgpjs v5.10 revoked key as revoked
- PB-29437 As an administrator I can log internal errors with the complete trace in Json format

### Security
- PB-30155 Update phpseclib/phpseclib to fix composer security vulnerability

### Fixed
- PB-30019 As a user I should not get a 500 when editing a user with payload containing integers as fields
- PB-29964 As an administrator disabling a user I should not get a 500 if the disabled date is not valid
- PB-29970 As a group manager I should receive an accurate summary in my notifications on group permission changes
- PB-29054 As an administrator I should not get an error when running the cleanup command and the users table does not exist
- PB-28719 As an administrator sending emails the timezone displayed in the emails should be in the correct time zone
- PB-30266 As an administrator sending emails with the email digest the message ID should be defined

### Maintenance
- PB-28247 Update cakephp/cakephp to version 4.5

## [4.6.0-rc.2] - 2024-03-13
### Fixed
- PB-30182 Build the styleguide on version 4.6.1

## [4.6.0-rc.1] - 2024-03-11
### Added
- PB-24485 As an administrator I can view the API healthcheck in the administration section
- PB-29396 As an administrator I can hide the share folder capability with a RBAC
- PB-25463 As an administrator I can disable the healthcheck index endpoint with a flag
- PB-29397 As an administrator I can disable the healthcheck administration panel with a flag

### Improved
- PB-29009 As an administrator completing my setup I should not receive a notification that I completed my setup
- PB-26152 The API should identify openpgpjs v5.10 revoked key as revoked
- PB-29437 As an administrator I can log internal errors with the complete trace in Json format

### Security
- PB-30155 Update phpseclib/phpseclib to fix composer security vulnerability

### Fixed
- PB-30019 As a user I should not get a 500 when editing a user with payload containing integers as fields
- PB-29964 As an administrator disabling a user I should not get a 500 if the disabled date is not valid
- PB-29970 As a group manager I should receive an accurate summary in my notifications on group permission changes
- PB-29054 As an administrator I should not get an error when running the cleanup command and the users table does not exist
- PB-28719 As an administrator sending emails the timezone displayed in the emails should be in the correct time zone
- PB-30266 As an administrator sending emails with the email digest the message ID should be defined

### Maintenance
- PB-28247 Update cakephp/cakephp to version 4.5

## [4.6.0-test.1] - 2024-03-07
### Added
- PB-24485 As an administrator I can view the API healthcheck in the administration section
- PB-29396 As an administrator I can hide the share folder capability with a RBAC
- PB-25463 As an administrator I can disable the healthcheck index endpoint with a flag
- PB-29397 As an administrator I can disable the healthcheck administration panel with a flag

### Improved
- PB-29009 As an administrator completing my setup I should not receive a notification that I completed my setup
- PB-26152 The API should identify openpgpjs v5.10 revoked key as revoked
- PB-29437 As an administrator I can log internal errors with the complete trace in Json format

### Security
- PB-30155 Update phpseclib/phpseclib to fix composer security vulnerability

### Fixed
- PB-30019 As a user I should not get a 500 when editing a user with payload containing integers as fields
- PB-29964 As an administrator disabling a user I should not get a 500 if the disabled date is not valid
- PB-29970 As a group manager I should receive an accurate summary in my notifications on group permission changes
- PB-29054 As an administrator I should not get an error when running the cleanup command and the users table does not exist
- PB-28719 As an administrator sending emails the timezone displayed in the emails should be in the correct time zone
- PB-30266 As an administrator sending emails with the email digest the message ID should be defined

### Maintenance
- PB-28247 Update cakephp/cakephp to version 4.5

## [4.5.2] - 2024-02-14
### Fixed
- PB-29621 As a user I should get a 400 if the locale passed in the URL is not a string
- PB-28867 As a user I should see an improved performance when requesting the folder index endpoint

### Improved
- PB-28635 As an administrator I can disable the email digest without having to change the command sending the emails

### Security
- PB-29680 Bump dependency composer/composer to v2.7.0

### Maintenance
- PB-29109 Support PHP 8.3 for passbolt API
- PB-29376 GITHUB-506 Bump dependency duosecurity/duo_universal_php to 1.0.2 (#506)
- PB-29514 Fix password expiry test which randomly fails
- PB-29625 Fix CI to support latest composer dependency version

## [4.5.2-test.1] - 2024-02-13
### Fixed
- PB-29621 As a user I should get a 400 if the locale passed in the URL is not a string
- PB-28867 Fix folder serialization performance

### Improved
- PB-28635 As a user I want to use one single command to send emails

### Security
- PB-29680 Bump dependency composer/composer to v2.7.0

### Maintenance
- PB-29109 Support PHP 8.3 for passbolt API
- PB-29376 GITHUB-506 Bump dependency duosecurity/duo_universal_php to 1.0.2 (#506)
- PB-29514 Fix password expiry test which randomly fails
- PB-29625 Fix CI to support latest composer dependency version

## [4.5.0] - 2024-02-08
### Added
- PB-23913 As a user I can see my passwords marked as expired when users lose permissions on these
- PB-23913 As an administrator I can activate the password expiry feature
- PB-28923 As a user I want to be able to use passbolt in Russian
- PB-21484 As an administrator I can define Microsoft 365 and Outlook providers in SMTP settings
- PB-19652 As an administrator I can cleanup groups with no members with the cleanup command
- PB-27707 As administrator, with RBAC I should be able to set “can see users workspace” to “Allow if group manager”
- PB-28716 Desktop application flag is now enabled by default
- PB-26203 Desktop app define the account kit exportation help page

### Improved
- PB-27616 Improve resources serialization performance on GET resources.json

### Security
- PB-29148 Bump selenium API plugin version to v4.5
- PB-29005 Upgrades phpseclib/phpseclib to fix composer audit security vulnerability
- PB-22336 As an admin I should be able to enable/disable request group managers to add users to groups emails separately (LDAP/AD)
- PB-28871 Mitigate supply chain attack on PR and lint lock files
- PB-28658 Mitigate supply chain attack on post npm install script

### Fixed
- PB-29200 Fixes the recover_user command (GITHUB #504)
- PB-29164 Fix recent InstallCommand changes breaking selenium tests
- PB-29132 Fix composer lock file not up-to-date message when installing dependencies
- PB-29160 Fix failing static analysis job in CI
- PB-29137 Fix failing in UsersEditDisableControllerTest file due to purifier
- PB-29113 Fix a typo in the email sent when admins lose their admin role
- PB-28130 Fix invalid cookie name should not trigger a 500
- PB-29007 Fix constantly failing test in RbacsUpdateControllerTest file
- PB-28991 Fix email queue entries not marked as sent

### Maintenance
- PB-28857 Require phpunit-speedtrap to track down slow tests
- PB-25516 Remove --dev from .gitlab test options, it has not effect and will break with composer v3
- PB-28844 Improves the methods testing email content
- PB-28845 Skip unauthenticated exception from logging
- PB-28653 Speed-up tests by mocking the client in healthcheck relevant tests

## [4.5.0-rc.1] - 2024-02-01
### Added
- PB-23913 As a user I can see my passwords marked as expired when users lose permissions on these
- PB-23913 As an administrator I can activate the password expiry feature
- PB-28923 As a user I want to be able to use passbolt in Russian
- PB-21484 As an administrator I can define Microsoft 365 and Outlook providers in SMTP settings
- PB-19652 As an administrator I can cleanup groups with no members with the cleanup command
- PB-27707 As administrator, with RBAC I should be able to set “can see users workspace” to “Allow if group manager”
- PB-28716 Desktop application flag is now enabled by default
- PB-26203 Desktop app define the account kit exportation help page

### Improved
- PB-27616 Improve resources serialization performance on GET resources.json

### Security
- PB-29148 Bump selenium API plugin version to v4.5
- PB-29005 Upgrades phpseclib/phpseclib to fix composer audit security vulnerability
- PB-22336 As an admin I should be able to enable/disable request group managers to add users to groups emails separately (LDAP/AD)
- PB-28871 Mitigate supply chain attack on PR and lint lock files
- PB-28658 Mitigate supply chain attack on post npm install script

### Fixed
- PB-29200 Fixes the recover_user command (GITHUB #504)
- PB-29164 Fix recent InstallCommand changes breaking selenium tests
- PB-29132 Fix composer lock file not up-to-date message when installing dependencies
- PB-29160 Fix failing static analysis job in CI
- PB-29137 Fix failing in UsersEditDisableControllerTest file due to purifier
- PB-29113 Fix a typo in the email sent when admins lose their admin role
- PB-28130 Fix invalid cookie name should not trigger a 500
- PB-29007 Fix constantly failing test in RbacsUpdateControllerTest file
- PB-28991 Fix email queue entries not marked as sent

### Maintenance
- PB-28857 Require phpunit-speedtrap to track down slow tests
- PB-25516 Remove --dev from .gitlab test options, it has not effect and will break with composer v3
- PB-28844 Improves the methods testing email content
- PB-28845 Skip unauthenticated exception from logging
- PB-28653 Speed-up tests by mocking the client in healthcheck relevant tests

## [4.5.0-test.1] - 2024-01-29
### Added
- PB-23913 As a user I can see my passwords marked as expired after a user loses permissions
- PB-23913 As an administrator I can activate the password expiry feature
- PB-23913 As a user I can define the expiry date of a passwor
- PB-23913 As an administrator I can define advanced password expiry settings
- PB-28923 As a user I want to be able to use passbolt in Russian
- PB-21484 Add support for Microsoft 365 and Outlook providers in SMTP settings
- PB-19652 Add cleanup task to check for groups with no members
- PB-27707 As administrator, with RBAC I should be able to set “can see users workspace” to ‘Allow if group manager’
- PB-28716 Enable desktop application flag by default
- PB-26203 Desktop app define the account kit exportation help page

### Improved
- PB-27835 As signed-in user configuring MFA TOTP I can see the TOTP secret so I can use it instead of the QR code
- PB-27616 Improve resources serialization performance on GET resources.json
- PB-28521 Add migration to alter gpgkeys.uid column length to 769

### Security
- PB-29148 Bump selenium API plugin version to v4.5
- PB-27760 As administrator, I can hide the administrator identity behind LDAP triggered emails
- PB-29005 Upgrades phpseclib/phpseclib to fix composer audit security vulnerability
- PB-22336 As an admin I should be able to enable/disable request group managers to add users to groups emails separately (LDAP/AD)
- PB-28871 Mitigate supply chain attack on PR and lint lock files
- PB-28658 Mitigate supply chain attack on post npm install script

### Fixed
- PB-29200 Fixes the recover_user command (GITHUB #504)
- PB-29164 Fix recent InstallCommand changes breaking selenium tests
- PB-29132 Fix composer lock file not up-to-date message when installing dependencies
- PB-29160 Fix failing static analysis job in CI
- PB-29137 Fix failing in UsersEditDisableControllerTest file due to purifier
- PB-27788 Correct gendered language on ldap sync ignore messages
- PB-29113 Fix a typo in the email sent when admins lose their admin role
- PB-28130 Fix invalid cookie name should not trigger a 500
- PB-29007 Fix constantly failing test in RbacsUpdateControllerTest file
- PB-28991 Fix email queue entries not marked as sent

### Maintenance
- PB-28636 Speed-up cloud tests by storing avatars on local storage
- PB-28857 Require phpunit-speedtrap to track down slow tests
- PB-25516 Remove --dev from .gitlab test options, it has not effect and will break with composer v3
- PB-28844 Improves the methods testing email content
- PB-28845 Skip unauthenticated exception from logging
- PB-28653 Speed-up tests by mocking the client in healthcheck relevant tests

## [4.4.2] - 2023-11-29
### Improved
- PB-27616 As a user I should see improved performances when retrieving resources on the GET resources.json entry point

### Fixed
- PB-28991 As a user emails should be resent if the first attempt failed

## [4.4.2-test.1] - 2023-11-27
### Improved
- PB-27616 As a user I should see improved performances when retrieving resources on the GET resources.json entry point

## [4.4.1] - 2023-11-20
### Improved
- PB-28521 Alter the gpgkeys.uid column length to 769 to enable the synchronisation of user with very long names

### Fixed
- PB-27957 As an administrator I should be notified that an administrator was deleted only when an administrator has been deleted, and not a regular user

### Maintenance
- PB-27927 Remove unused user_agents table
- PB-28616 Refactor the email digest plugin code for easier usage and maintainability

## [4.4.1-test.3] - 2023-11-20
### Fixed
- PB-27616 Revert performance improvements as further investigation is required

## [4.4.1-test.2] - 2023-11-20
### Maintenance
- PB-28521 Alter the gpgkeys.uid column length to 769

## [4.4.1-test.1] - 2023-11-17
### Improved
- PB-27616 As a user I should see improved performances when retrieving resources on the GET resources.json entry point

### Fixed
- PB-27957 As an administrator I should be notified that an administrator was deleted only when an administrator has been deleted, and not a regular user

### Maintenance
- PB-27927 The unused user_agents table is now removed from the database
- PB-28616 The email digest plugin code was deeply refactored for enhanced usage and maintainability

## [4.4.0] - 2023-11-07
### Added
- PB-27773 As an administrator I can deny access to the mobile setup screen with RBAC
- PB-27951 As system operator I should be warned in the healthcheck when using PHP < 8.1, as support for PHP versions 7.4 and 8.0 will soon be removed

### Improved
- PB-27948 Guest identification by their username should be case-insensitive, unless specified in configuration
- PB-27957 Send notifications to all administrators when an administrator is deleted
- PB-27941 Send notifications to administrators when an administrator loses its administrator role
- PB-28171 Enable the email digest by default

### Security
- PB-28274 Fixes an XSS Security issue with mail content sanitization

### Fixed
- PB-25477 As an administrator, I should be able to recreate a user with an email that exists in the db via the command line
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8
- PB-27857 Fix help site release notes automation by adding flavour on help site release notes merge request

### Maintenance
- PB-27932 Improve code static by using cakedccakephp/phpstan
- PB-28079 Remove deprecation warnings from the test suite

## [4.4.0-test.3] - 2023-11-06
### Added
- PB-28537 As a user I should receive email digests translated in my locale

## [4.4.0-rc.1] - 2023-11-03
### Added
- PB-27773 As an administrator I can deny access to the mobile setup screen with RBAC
- PB-27951 As system operator I should be warned in the healthcheck when using PHP < 8.1, as support for PHP versions 7.4 and 8.0 will soon be removed

### Improved
- PB-27948 Guest identification by their username should be case-insensitive, unless specified in configuration
- PB-27957 Send notifications to all administrators when an administrator is deleted
- PB-27941 Send notifications to administrators when an administrator loses its administrator role
- PB-28171 Enable the email digest by default

### Security
- PB-28274 Fixes an XSS Security issue with mail content sanitization

### Fixed
- PB-25477 As an administrator, I should be able to recreate a user with an email that exists in the db via the command line
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8
- PB-27857 Fix help site release notes automation by adding flavour on help site release notes merge request

### Maintenance
- PB-27932 Improve code static by using cakedccakephp/phpstan
- PB-28079 Remove deprecation warnings from the test suite

## [4.4.0-test.2] - 2023-10-30
### Added
- PB-28482 Styleguide version bump to v4.4.0

## [4.4.0-test.1] - 2023-10-27
### Added
- PB-27773 As an administrator I can deny access to the mobile setup screen with RBAC
- PB-27951 As system operator I should be warned in the healthcheck when using PHP < 8.1, as support for PHP versions 7.4 and 8.0 will soon be removed

### Improved
- PB-27948 Guest identification by their username should be case-insensitive, unless specified in configuration
- PB-27957 Send notifications to all administrators when an administrator is deleted
- PB-27941 Send notifications to administrators when an administrator loses its administrator role
- PB-28171 Enable the email digest by default

### Security
- PB-28274 Fixes an XSS Security issue with mail content sanitization

### Fixed
- PB-25477 As an administrator, I should be able to recreate a user with an email that exists in the db
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8
- PB-27857 Fix help site release notes automation by adding flavour on help site release notes merge request

### Maintenance
- PB-27932 Improve code static by using cakedccakephp/phpstan
- PB-28079 Remove deprecation warnings from the test suite

## [4.3.0] - 2023-09-26
### Added
- PB-25405 As an administrator installing passbolt through the web installer, I should be able to configure authentication method for SMTP
- PB-25185 As a signed-in user on the browser extension, I want to export my account to configure the Windows application
- PB-25944 As an administrator I can define the schema on installation with Postgres
- PB-25497 As an administrator I can disable users (experimental)

### Improved
- PB-25999 Performance optimisation of update secret process
- PB-26097 Adds cake.po translation files for all languages supported by CakePHP

### Security
- PB-25827 As a user with encrypted message enabled in the email content visibility, I would like to see the gpg message encrypted with my key when a password is updated

### Fixed
- PB-25802 As a user I want to see localized date in my emails
- PB-25863 Fix emails not sent due to message-id header missing
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8

### Maintenance
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select provider endpoint

## [4.3.0-test.2] - 2023-09-25
### Fixed
- PB-27799 As an administrator installing passbolt on PostgreSQL, the database encoding should be set to utf-8

## [4.3.0-rc.1] - 2023-09-21
### Added
- PB-25405 As an administrator installing passbolt through the web installer, I should be able to configure authentication method for SMTP
- PB-25185 As a signed-in user on the browser extension, I want to export my account to configure the Windows application
- PB-25944 As an administrator I can define the schema on installation with Postgres
- PB-25497 As an administrator I can disable users (experimental)

### Improved
- PB-25999 Performance optimisation of update secret process
- PB-26097 Adds cake.po translation files for all languages supported by CakePHP

### Security
- PB-25827 As a user with encrypted message enabled in the email content visibility, I would like to see the gpg message encrypted with my key when a password is updated

### Fixed
- PB-25802 As a user I want to see localized date in my emails
- PB-25863 Fix emails not sent due to message-id header missing

### Maintenance
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select provider endpoint

## [4.3.0-test.1] - 2023-09-15
### Added
- PB-25497 As an administrator I can disable users
- PB-25405 As an administrator installing passbolt through the web installer, I should be able to configure authentication method for SMTP
- PB-25185 As a signed-in user on the browser extension, I want to export my account to configure the Windows application (disabled by default)
- PB-25944 As an administrator I can define the schema on installation with Postgres

### Improved
- PB-25999 Improve performance of update secret process
- PB-26097 Adds cake.po translation files for all languages supported by CakePHP

### Security
- PB-25827 As a user with encrypted message enabled in the email content visibility, I would like to see the gpg message encrypted with my key when a password is updated

### Fixed
- PB-25802 As a user I want to see localized date in my emails
- PB-25863 Set message-id header in emails

### Maintenance
- PB-25894 Run CI on postgres versions 13 and 15 instead of version 12 only
- PB-25969 As a developer, I can render emails in tests with html special chars
- PB-26107 Upgrade the cakephp/chronos library
- PB-26159 Update singpolyma/openpgp-php to improve compatibility with PHP 8.2
- PB-25247 Add integration tests on the MFA select endpoint

## [4.2.0] - 2023-08-24
### Added
- PB-24987 As an administrator I can define the password policies from the administration UI
- PB-25462 As an administrator I can deactivate RBACs with a feature flag
- PB-25036 As an administrator I can select PostgreSQL as database driver on installation
- PB-21403 As an administrator I can purge the email queue table from the command line

### Improved
- PB-24990 Performance optimisation of the cleanup command responsible to delete secrets without permissions
- PB-25263 Performance optimisation of the entry point retrieving the folders activity logs
- PB-25264 Performance optimisation of all the SQL queries retrieving user profiles
- PB-25199 Lower case UUIDs given as requests parameters before marshalling and persisting data
- PB-25389 As an administrator healthcheck/status.json requests should not be logged in the action_logs table
- PB-25734 As a user I do not want the first letters of my first and last names upper-cased when my profile is saved

### Security
- PB-25181 CSRF cookie should have secure flag set when site is served under HTTPs
- PB-25798 Fixes laminas/laminas-diactoros vulnerability by using the longwave/laminas-diactoros package

### Fixed
- PB-25472 As a user I can use an SMTP server using NTLM authentication
- PB-25475 As an administrator running the healthcheck, I should be warned for self-signed and wildcard certs instead of having a failure
- PB-25720 As an administrator I should not see a false error in the healthcheck when reading the App.base config

### Maintenance
- PB-21412 Upgrade phpstan to v1.10.15
- PB-21413 Upgrade psalm version to v5.12.0
- PB-21414 Upgrade cakephp codesniffer to v4.7
- PB-21672 Bump lorenzo/cakephp-email-queue package to 5.1
- PB-21917 Bump bcrowe/cakephp-api-pagination to v3.0.0
- PB-21918 Bump spomky-labs/otphp to v10.0.3
- PB-21919 Update enygma/yubikey package
- PB-22052 Passbolt test data version bump to v4.1.0
- PB-25379 Update vierge-noire/cakephp-fixture-factories package
- PB-24575 As a developer release notes should be automatically published on Github on new tag release
- PB-25471 As a developer Crowdin should export only a selected subset of languages
- PB-25801 As a developer I can create unpublished test packages

## [4.2.0-rc.2] - 2023-08-23
### Fixed
- PB-25964 As a user login with JWT authentication the verify-token in the challenge should not be lower cased

## [4.2.0-rc.1] - 2023-08-23
### Added
- PB-24987 As an administrator I can define the password policies from the administration UI
- PB-25462 As an administrator I can deactivate RBACs with a feature flag
- PB-25036 As an administrator I can select PostgreSQL as database driver on installation
- PB-21403 As an administrator I can purge the email queue table from the command line

### Improved
- PB-24990 Performance optimisation of the cleanup command responsible to delete secrets without permissions
- PB-25263 Performance optimisation of the entry point retrieving the folders activity logs
- PB-25264 Performance optimisation of all the SQL queries retrieving user profiles
- PB-25199 Lower case UUIDs given as requests parameters before marshalling and persisting data
- PB-25389 As an administrator healthcheck/status.json requests should not be logged in the action_logs table
- PB-25734 As a user I do not want the first letters of my first and last names upper-cased when my profile is saved

### Security
- PB-25181 CSRF cookie should have secure flag set when site is served under HTTPs
- PB-25798 Fixes laminas/laminas-diactoros vulnerability by using the longwave/laminas-diactoros package

### Fixed
- PB-25472 As a user I can use an SMTP server using NTLM authentication
- PB-25475 As an administrator running the healthcheck, I should be warned for self-signed and wildcard certs instead of having a failure
- PB-25720 As an administrator I should not see a false error in the healthcheck when reading the App.base config

### Maintenance
- PB-21412 Upgrade phpstan to v1.10.15
- PB-21413 Upgrade psalm version to v5.12.0
- PB-21414 Upgrade cakephp codesniffer to v4.7
- PB-21672 Bump lorenzo/cakephp-email-queue package to 5.1
- PB-21917 Bump bcrowe/cakephp-api-pagination to v3.0.0
- PB-21918 Bump spomky-labs/otphp to v10.0.3
- PB-21919 Update enygma/yubikey package
- PB-22052 Passbolt test data version bump to v4.1.0
- PB-25379 Update vierge-noire/cakephp-fixture-factories package
- PB-24575 As a developer release notes should be automatically published on Github on new tag release
- PB-25471 As a developer Crowdin should export only a selected subset of languages
- PB-25801 As a developer I can create unpublished test packages

## [4.1.2] - 2023-07-26
### Fixed
- PB-25472 Fix emails not sent for SMTP server using NTLM authentication

### Maintenance
- PB-25471 Crowdin should export only a selected subset of languages

## [4.1.2-rc.2] - 2023-07-25
### Fixed
- PB-25472 Fix emails not sent for SMTP server using NTLM authentication

### Maintenance
- PB-25471 Crowdin should export only a selected subset of languages

## [4.1.1] - 2023-07-13
### Fixed
- PB-25304 As an administrator the application healthcheck should compare passbolt version with the latest stable release
- PB-25325 As an administrator running the database healthcheck I should not see a false fail on the default database content

## [4.1.1-rc.2] - 2023-07-11
### Fixed
- PB-25304 As an administrator the application healthcheck should compare passbolt version with the latest stable release

## [4.1.1-rc.1] - 2023-07-11
### Fixed
- PB-25325 As an administrator running the database healthcheck I should not see a false fail on the default database content

## [4.1.0] - 2023-06-29
### Added
- PB-24259 As an administrator I can define with role based access control users' rights

### Improved
- PB-24744 As a LU the date time format in the response always display the time zone
- PB-24929 As a LU with multiple MFA providers setup, the latest provider used is proposed by default
- PB-24488 Non-JSON request should return a 404 if JSON is required
- PB-24617 As LU I want improved performance while sharing a folder with a user

### Security
- PB-25030 As an admin I can set a feature flag to prevent user email enumeration
- PB-24273 As an admin I can disable the GET auth/logout.json endpoint (enabled by default)
- PB-19510 As a user I should be redirected to HTTPS if SSL FORCE configuration is true
- PB-24566 As an admin the email settings password should be masked in the test email command log output
- PB-23591 As a user authenticating I can perform a limited amount of TOTP MFA attempts

### Fixed
- PB-24658 As an admin I should see no false warning in the email notification configuration section
- PB-25275 As an admin I should see the option page during installation after creating the server GPG keys
- PB-25276 As an admin on installation SSL force option should be set to true if the installation is launched over https
- PB-25274 Set force SSL config to false by default

### Maintenance
- PB-24925 Updates the fixture factories to its latest version
- PB-24913 Removes "type" from required JSON schema definition for TOTP resource types
- PB-24305 Recovery and register legacy routes are not used in emails and commands outputs
- PB-21604 Extract composer audit task from checkstyle job and make it non-blocking
- PB-21641 Rename check-style job to static-analysis and make it blocking

## [4.1.0-rc.3] - 2023-06-29
### Fixed
- PB-25275 As an admin I should see the option page during installation after creating the server GPG keys

## [4.1.0-rc.2] - 2023-06-28
### Fixed
- PB-24273 As an admin I can disable the GET auth/logout.json endpoint (enabled by default)
- PB-25274 Set force SSL config to false by default
- PB-25276 Webinstaller SSL force option should be set to true if the installation is launched over https

## [4.1.0-rc.1] - 2023-06-26
### Added
- PB-24259 As an administrator I can define with role based access control users' rights

### Improved
- PB-24744 As a LU the date time format in the response always display the time zone
- PB-24929 As a LU with multiple MFA providers setup, the latest provider used is proposed by default
- PB-24488 Non-JSON request should return a 404 if JSON is required
- PB-24617 As LU I want improved performance while sharing a folder with a user

### Security
- PB-25030 As an admin I can set a feature flag to prevent user email enumeration
- PB-24273 As an admin I can enable the GET auth/logout.json endpoint (disabled by default)
- PB-19510 As a user I should be redirected to HTTPS if SSL FORCE configuration is true
- PB-24566 As an admin the email settings password should be masked in the test email command log output
- PB-23591 As a user authenticating I can perform a limited amount of TOTP MFA attempts

### Fixed
- PB-24658 As an admin I should see no false warning in the email notification configuration section

### Maintenance
- PB-24925 Updates the fixture factories to its latest version
- PB-24913 Removes "type" from required JSON schema definition for TOTP resource types
- PB-24305 Recovery and register legacy routes are not used in emails and commands outputs
- PB-21604 Extract composer audit task from checkstyle job and make it non-blocking
- PB-21641 Rename check-style job to static-analysis and make it blocking

## [4.0.2] - 2023-05-25
### Fixed
- PB-24644 As an admin I should be able to run migrations on a 32 bit environment

## [4.0.2-rc.1] - 2023-05-24
### Fixed
- PB-24644 As an admin I should be able to run migrations on a 32 bit environment

## [4.0.1] - 2023-05-23
### Added
- PB-24644 As an admin I should be able to run migrations on a 32 bit environment

## [4.0.1-rc.1] - 2023-05-19
### Added
- PB-24644 As an admin I should be able to run migrations on a 32 bit environment

## [4.0.0] - 2023-05-16
### Added
- PB-24245 As LU using the API I can manage standalone TOTP and TOTP associated with passwords resources types
- PB-24086 As an admin I can create a user recovery link from the command line

### Maintenance
- PB-23321 Upgrade CakePHP to 4.4
- PB-24296 As a developer I can retrieve in integration tests the body of json requests in array
- PB-24083 Removes the usage of the Paginator deprecated in CakePHP 4.4
- PB-23926 Bump PHPUnit to ~9.5.2 to avoid warning messages of 9.6
- PB-22758 Introduce JWT key injection to enable parallel tests
- PB-22622 Add CS rule to disallow space after NOT operator
- PB-23786 Remove PHP 7.3 from the testing pipes
- PB-24561 Upgrades cakephp/migrations library
- PB-24073 As a developer I should ensure that the CHANGELOG.md file is in the right format
- PB-24071 As a developer I can enable feature plugins with the plugins class name
- PB-24272 Adds contribution link in CONTRIBUTING.md

### Fixed
- PB-24078 As a user I should receive the correct email avatar text after folder manipulation
- PB-24039 Action log event listener should not throw error on missing connection
- PB-23558 Remove PHP 8.2 deprecation warnings
- PB-23557 Remove PHP 8.1 deprecation warnings

### Security
- PB-24056 As an admin I can view log stack traces when debug mode is enabled
- PB-24297 Update guzzlehttp/psr7 to fix composer audit security vulnerability

## [4.0.0-rc.5] - 2023-05-11
### Maintenance
- PB-24561 Upgrades cakephp/migrations library

## [4.0.0-rc.4] - 2023-05-05
### Fixed
- PB-24561 - Styleguide version bump to v4.0.3

## [4.0.0-rc.3] - 2023-04-28
### Fixed
- PB-24051 Fixes field obfuscation not to obfuscate the first element in pure array
- PB-24470 Fixes recover_user command not showing while running passbolt -h

## [4.0.0-rc.2] - 2023-04-26
### Added
- PB-24245 Adds two TOTP resource types feature flag
- PB-24086 As an admin, I can create a user recovery token from the command line
- PB-24056 As an admin I can view log stack traces when debug mode is enabled

### Improved
- PB-24073 As a developer I should ensure that the CHANGELOG.md file is in the right format
- PB-24071 As a developer I can enable feature plugins with the plugins class name
- PB-24272 Adds contribution link in CONTRIBUTING.md

### Maintenance
- PB-23321 Upgrade CakePHP to 4.4
- PB-24297 Update guzzlehttp/psr7 to fix composer audit security vulnerability
- PB-24296 As a developer I can retrieve in integration tests the body of json requests in array
- PB-24083 Removes the usage of the Paginator deprecated in CakePHP 4.4
- PB-23926 Bump PHPUnit to ~9.5.2 to avoid warning messages of 9.6
- PB-22758 Introduce JWT key injection to enable parallel tests
- PB-22622 Add CS rule to disallow space after NOT operator
- PB-23786 Remove PHP 7.3 from the testing pipes

### Fixed
- PB-24078 As a user I should receive the correct email avatar text after folder manipulation
- PB-24039 Action log event listener should not throw error on missing connection
- PB-23558 Remove PHP 8.2 deprecation warnings
- PB-23557 Remove PHP 8.1 deprecation warnings

## [3.12.2] - 2023-04-25
### Security
- PB-24315 As signed-in user creating resources with encrypted description the API should not store unencrypted descriptions even if provided by the client

## [3.12.0] - 2023-03-15
### Added
- PB-20535 As a community user I want to use folders
- PB-22749 As an administrator I can customise passbolt to output the action logs in syslog
- PB-22749 As an administrator I can customise passbolt to output the action logs in a file
- PB-22749 As an administrator I can implement my own action logs handler

### Fixed
- PB-23717 As a user using the json API I should get a bad request error instead of an internal error if using api-version=v1
- PB-21826 Fix emails entries should not be locked when threshold limit is exceeded
- PB-23519 As an administrator running the DUO v4 migration I should not see a warning message if DUO was not configured
- PB-23721 As an administrator I want to be sure the server key is in the keyring before decrypting users directory settings

### Security
- PB-23311 As an administrator I should be the only one to know which users have enabled MFA

### Improved
- PB-23333 As an administrator I should see a notice instead of a warning if I enabled the self registration plugin
- PB-23722 As a developer running the unit tests I want to be sure the version from the config matches the one from the changelog
- PB-22892 As a user recovering my account I want to see the success and error pages feedback

### Maintenance
- PB-23287 Duo multi-factor authentication redirection refactoring
- PB-23702 Update phpseclib/phpseclib dependency

## [3.11.1] - 2023-03-03
### Fixed
- PB-23283 As an administrator I can disable username validation in Duo Callback endpoints

## [3.11.0] - 2023-03-01
### Added
- PB-22741 As an administrator I should see an error in the healthcheck if I use php 7.3 or less
- PB-22747 As an administrator I can define a regular expression to customise email validation
- PB-22866 As a user I want to use passbolt in Italian
- PB-22866 As a user I want to use passbolt in Portuguese (Brazil)
- PB-22866 As a user I want to use passbolt in Korean
- PB-22866 As a user I want to use passbolt in Romanian

### Fixed
- PB-21489 As a user I should not see double headers in emails sent by the email digest

### Improved
- PB-22725 As an administrator I want to manage Duo v4 settings
- PB-21906 As a user I don’t want to receive email by default when I create a resource or a folder as well as I don’t want to see any details for this content by default

### Maintenance
- PB-22416 As a developer I can safely deactivate plugins between solutions
- PB-22756 Fixes a range of failing pagination tests
- PB-22495 Refactors the SmtpTransport to enhance the code coverage of emails

## [3.10.0] - 2023-02-09
### Added
- PB-19784 As a user I can self register if my email domain matches the policy defined by the administrators

### Improved
- PB-21485 As a server administrator I want to configure the list of active proxies the instance is behind in order to get client IP when necessary
- PB-21682 As an administrator I want to configure the client option of the SMTP settings
- PB-22019 As a server administrator I want to configure TOTP MFA secret length

### Maintenance
- PB-22327 env variable PASSBOLT_PLUGINS_SMTP_SETTINGS renamed in PASSBOLT_PLUGINS_SMTP_SETTINGS_ENABLED (backward compatible)
- PB-22406 curl and openssl extensions requirements added
- PB-22413 bump CakePHP to ^4.3.11

## [3.9.0] - 2023-01-17
### Added
- PB-20539 As a user I can protect the authentication to passbolt with a second factor method

### Fixed
- PB-19601 As an admin running the healthcheck I should not see an unmanaged error if DB connection fails
- PB-21497 GITHUB-437 As an administrator I should see default user avatar in the email I receive when a user complete the setup
- PB-21501 GITHUB-411 As an administrator I should see the correct path relative to config tips in the health check report
- PB-21551 As an administrator I should be able to update with the web installer without getting an error due the subscription
- PB-21756 As an anonymous user switching MFA provider I should be redirected to the original target
- PB-21756 As an anonymous user verifying my second factor I should not get a 500 error due to an improperly sanitized redirect parameter

### Improved
- PB-19653 Rename Google authenticator into Totp authenticator
- PB-19807 As an administrator I want to know if email hostname availability is enabled in the health check report
- PB-20985 As an administrator I shouldn't be able to send a test email in command line without defining the recipient
- PB-21502 As an administrator I want to know if I run a passbolt command without using the webserver user
- PB-21635 As an administrator I want to the cron events to be logged
- PB-21751 As anonymous user I don't want to see the TOTP field auto-completed when I verify my second factor authentication
- PB-19715 As an administrator I want to lock the SMTP settings entry points

### Maintenance
- PB-19212 Improve PHPUNIT performances
- PB-19541 Add composer audit job on development pipelines
- PB-19594 Avoid duplicated pipelines
- PB-19583 Remove deprecated usage of dummy auth token generation in tests
- PB-19594 Improve phpunit pipelines environment matrix
- PB-19706 Refactor favorites add controller into service
- PB-19707 Refactor favorites delete controller into service
- PB-20512 Ease debug by attaching original exception to InternalErrorException when missing
- PB-20541 Replace usage of Cake core Exception with CakeException when not done yet
- PB-21361 Remove deprecated usage of authenticateAs in tests
- PB-21658 Add support to PHP 8.2

## [3.8.3] - 2022-11-30
### Fixed
- PB-21631 Ensure the OpenPGP server key is in the keyring prior to sending any emails

## [3.8.1] - 2022-11-17
### Fixed
- PB-21478 As an administrator, I should be able to edit SMTP settings having a sender email not being a valid email
- PB-21438 As an administrator using docker, I should be able to access the SMTP settings of my organization
- PB-21486 As an administrator, I can define the SMTP authentication method via the SMTP admin workspace
- PB-21481 As an administrator, I want emails to be sent with the sender settings defined in database, if defined in the database

## [3.8.0] - 2022-11-09
### Added
- PB-19192: As an administrator, I want to manage SMTP settings in the administration workspace
- PB-19151: As a user, I want to use passbolt with the Solarized light theme
- PB-19151: As a user, I want to use passbolt with the Solarized dark theme

### Improved
- PB-19200: GpgAuthenticator now asserts the message is a valid OpenPGP message prior to decryption on stage 0

### Fixed
- PB-19312: As a logged-in user, I want to see my first name and last name correctly displayed in email headers
- PB-18718: As a logged-in user, I want my locale not to be overwritten by the server config on pages served by the server
- PB-19261: As a logged-in user, I should not get an internal error if no filter is passed to the get resource.json entry point

### Security
- PB-19090: Protect forms from spell-jacking attack

### Maintenance
- PB-19235: Migrate comments controllers logic into services
- PB-19603: Cover additional “add user to group” case: As group manager I can add a user to a group which have no resources shared with
- PB-6081: Move community plugins into plugins/PassboltCe
- PB-19621: Stop changing folders permissions in installation tests
- PB-19255 As an administrator I can trigger 500 errors on demand to test my logs

## [3.7.3] - 2022-09-27
### Security
- PB-19090 Protect forms from spell-jacking attack

## [3.7.2] - 2022-09-20
### Fixed
- PB-18380 Let passbolt-configure script setup certbot for RHEL9 support
- PB-16983 Handles the lack of permissions on image directory when deleting
- PB-16898 Redesign download a supported browser to get started

### Improved
- PB-18650 Add a check on mysql status in order to run mysql commands only when it's ready in unit tests
- PB-18664 Add retry logic to Gitlab CI jobs

## [3.7.1] - 2022-08-10
- PB-18381 Fix source language typos
- PB-18397 Fix as an admin I can generate a server key with the webinstaller within an instance over http
- PB-17096 Fix resouce_types name and slug postgresql compatibility
- PB-18372 Bump styleguide version to 3.7.1

## [3.7.0] - 2022-07-28
### Added
- PB-17098 Add rockylinux 9 support
- PB-16751 Add Redhat 9 support
- PB-16749 Add Ubuntu 22.04 support
- PB-16950 Add Spanish and Lithuanian support
- PB-14514 Add PHP8.0 support
- PB-14514 Fix PHP8.1 compatibility issues
- PB-16161 Create action log endpoint for user CRUD
- PB-16844 Common part of the user recovery and setup audit log

### Security
- PB-17068 PBL-07-002 Fix key algorithm validation should be set to strict on setup
- PB-17068 Fix OpenPGP unarmor should use base64_decode in strict mode
- PB-17068 SEC-1292 Fix unsafe default recipient email address (Credit: Ashley Primo)

### Fixed
- PB-16705 As group manager updating group memberships I should not get a timeout
- PB-16949 As group manager deleting a group user the operation should not be slowed down by the folders plugin
- PB-16705 As a group manager updating group memberships I should not get a timeout due to a plugin integration
- PB-17068 Fix GroupsUsersValidatorTest psr-4 autoloading warning
- PB-17007 As AD performing a cleanup of the missing folders relations I should not get a timeout
- PB-16749 Fix jobs to reuse last job artifact instead of rebuilding it everytime
- PB-16877 Fixes ClearMfaCookieOnSetupAndRecover for controllers without User component
- PB-16666 GITHUB-432 Fix healthcheck style

### Maintenance
- PB-17009 Replace createrepo by createrepo_c
- PB-16956 Misc Fixture Factories refactoring
- PB-16956 Modernize folders plugin bootstrap, add src/Plugin.php file
- PB-16806 UacAwareMiddleware trait now return UAC exclusively. More typing in UAC object.
- PB-16161 Renames ambiguous testing traits
- PB-16161Add and enhance log related factories
- PB-16791 Upgrade webinstaller openpgpjs to v5
- PB-14514 Update to composer v2.2 + Fix CI jobs
- PB-16657 Remove mariadb dependency
- PB-16161 Refactor to split folder, resource and user related logic in respective classes

## [3.6.0]
### Improved
- PB-9739 OpenPGP key and message validation refactoring
- PB-14141 Enhanced public/private key validation rules
- PB-13685 Enhanced secret validation rules
- PB-14138 Refactor setup and recover related controllers with dependency injection
- PB-14510 Three trivial endpoints, such as GET on login are not logged anymore

### Security
- PB-14400 Upgrade firebase/php-jwt to 6.1

### Fixed
- PB-14369 Fixes email settings issues in the test suite
- PB-15046 Handle user lost-passphrase scenarios with API <= v3.5

### Maintenance
- PB-14812 Upgrade cakephp/cakephp to 4.3

## [3.5.0] - 2021-01-12
### Added
- PB-13161 As LU I should be able to use passbolt with my Android mobile
- PB-13161 As LU I should be able to use passbolt with my IOS mobile
- PB-5967 As AD I can use passbolt with a PostgreSQL database provider [experimental]
- PB-5967 As AD I can migrate an existing instance to PostgreSQL with the help of the command line [experimental] and MySQL to Postgres migration tools, e.g. as described here: https://pgloader.readthedocs.io and here: https://pgloader.io/.
- PB-8513 As LU I can request gpg keys using pagination
- PB-13321 As a user I can use passbolt in Dutch
- PB-13321 As a user I can use passbolt in Japanese
- PB-13321 As a user I can use passbolt in Polish

### Improved
- PB-12817 As LU I can import avatars having a jpeg extension
- PB-12943 As AD I should be able to see log when a user tries to sign-in with an invalid bearer token
- PB-12888 Improve performances of the operations requiring permissions accesses by replacing the single index on type by a combined index involving the requested columns
- PB-13177 As AD I should be able to see any gpg keys errors from the healthcheck
- PB-13183 As LU I should be able create resource having a name or a username of 255 characters long
- PB-13265 As AD I can create a JWT key pair even if the database is not set
- PB-13164 As AD I can cleanup duplicate entries in the favorites tables, groups_users and permissions

### Security
- PB-13217 PBL-06-011 Fix ACL on mobile transfer view controller

### Fixed
- PB-9887 Fix as AD I can send email digest from the /bin/cron script
- PB-12957 Fix multiple language issues reported by community
- PB-12914 Fix as a group manager I should not get multiple notifications when a group is updated
- PB-13158 As AD I should see a tip with proper directory permissions when the JWT assets healthcheck fails

### Maintenance
- PB-12835 Move users setup/recover/register controllers logic into services to welcome the upcoming account recovery feature

## [3.4.0] - 2021-12-07
### Added
- PB-9826 As a user I want to use passbolt natively on Edge
- PB-8371 As LU I want to see the login/MFA/recover/register screens in dark mode

### Improvement
- PB-8522 As LU I should see the MFA verify field having focus
- PB-9730 As AD I should be able to check avatars read issues from the healthcheck

### Fix
- PB-8932 Fix as LU I should see an animation when I successfully configured MFA
- PB-9286 Fix as LU I should see the locale dropdown field of the setup/recover screen well positioned
- PB-9397 Fix as AD I shouldn't see an error on the healthcheck if the JWT auth is disabled and I never configured it
- PB-9114 Fix as lu I should be able to upload a transparent avatar in .png format.
- PB-9750 Fix spelling mistakes reported by the community
- PB-9762 Fix requesting /auth/login.json should not trigger an unexpected error
- PB-9888 Fix MFA & JWT refresh token issue, remove Bearer from the hashed session identifier
- PB-12817 Fix as LU I should be able to update jpeg avatar

### Security
- PB-7374 As soft deleted but logged in user I should be forbidden to request the API
- PB-9340 Fix email queue data should be stored and deserialized as json and not php

### Maintenance
- PB-9311 Refactor JWT and MFA plugins for better code maintainability.
- PB-8320 Implement the tests that are marked as incomplete for cleaner continuous integration test reports
- PB-8211 Psalm set to level 4
- PB-9726 Fix do not load cleanup tasks unless in CLI mode
- PB-9753 Improve table fields validation tests, do not save entity when testing the validation of properties
- PB-9310 Move avatar file_storage logic into AvatarsTable
- PB-9785 Update JWT healthcheck help messages
- PB-9656 Migrate fields from utf8mb4 to a more performant encoding when possible

## [3.3.1] - 2021-11-24
### Security fixes
- PB-9820 / PBL-06-008 WP3: JWT key confusion leads to authentication bypass (High) [experimental][disabled by default]

## [3.3.0] - 2021-10-25
### Added
- PB-7815 As a server administrator I should be able to enable / disable the in-form menu feature, enabled by default
- PB-6072 As a server administrator I should be able to enable / disable the password generator feature, enabled by default
- PB-8189 As a user I should be able to use the application in German or Swedish
- PB-7847 As AN I should be able to authenticate to passbolt via JWT access and refresh tokens [experimental][disabled by default]
- PB-6034  As LU I should be able to configure my mobile app [experimental][disabled by default]

### Improvement
- PB-8908 As a user I should see the footer of the passbolt emails translated with my locale
- PB-8364 As a user I should see the subject of the passbolt emails translated with my locale
- PB-6032 As API user I shouldn’t see the _joinData properties in the resource entry points responses
- PB-8281 Add Debian 11 bullseye support
- PB-7750 As AD I should be notified by the healthcheck when a tmp files is executable
- PB-7760 Increase PHPStan level to 6
- PB-8081 As AD I should be able to configure passbolt over IPv6 while installing a passbolt package
- PB-5866 As AD I should be able to detect avatar data discrepancies using the passbolt cleanup command
- PB-7605 As a developer I should be able to enable/disable a plugin easily

### Fixed
- PB-5457 Fix as LU importing a batch of passwords I should not get an internal errors because of database deadlock
- PB-7840 Fix as AD I can install/reconfigure the passbolt package if ssl certificates are already present

### Security
- PB-8047 Fix PBL-02-002 As LU I should logout by posting to the API and the entry point should should be protected by CSRF
- PB-7751 Updates FlySystem dependency to v2.1.1
- SEC-181 Fix information disclosure: recover endpoint should not return user role and name.

### Maintenance
- PB-8488 Remove user agent unnecessary check associated with MFA token
- PB-8336 Clean phpunit.xml file
- PB-8448 Hashes the session ID prior to passord_hash
- PB-8210 Replaces PHPSESSID with session_name()

## [3.2.1] - 2021-06-04
### Fixed
- GITHUB-402 Fix API v3 regression, login must accept JSON data

## [3.2.0] - 2021-05-31
### Added
- PB-5054 French internationalization
- PB-5171 As logged-in user I can paginate the result of the users and resources index controllers
- PB-5854 As logged-in user I can save the locale of a user as account setting
- PB-5854 As admin I can save the locale the organization as organization setting

### Fixed
- PB-5523 Fix as system administrator I should see the healthcheck errors colored in red
- PB-5860 Fix password max length should be set to 4096 in resource type definitions
- PB-6031 Fix as LU I shouldn't get a fatal error when using a scalar instead of an array for some filters values
- PB-6131 Fix healthcheck error messages display

### Improved
- PB-5975 Test code with PHPStan - level 4
- Avatar table should use created and modified for timestamp and not created_at and modified_at
- Move avatar in database

### Maintenance
- PB-5527 Migration to CakePHP4

### Security
- Remove X-XSS-Protection as per Cure53 audit recommendations

## [3.1.0] - 2021-03-17
### Added
- Add preview password plugin feature flag

## [3.0.2] - 2021-03-09
### Fixed
- GITHUB-378 Fix healthcheck ssl fullBaseUrl check
- Fix email digest email preview should accept empty (null) template
- Fix send test email command should accept undefined username and password

## [3.0.1] - 2021-02-24
### Fixed
- Fix resources population of resource_type_id field migration

## [3.0.0] - 2021-02-18
### Deprecated
- Drop support for API format v1, api-version parameter is deprecated
- Remove title from API response envelope format
- Drop support for PHP < v7.3, application require PHP v7.3 by default
- Drop support for Composer < v2, application requires Composer v2 by default

### Added
- Add dark theme to the community edition
- Add new system check utilities in ./bin, for example ./bin/status-report
- Add web installer automatically populates mysql credentials (VM / Debian Package)
- Add support for multiple resource types
- Add resource with encrypted description as resource type
- Add generic cron job task in ./bin/cron
- Add support for untracked personal shell scripts under ./bin/my
- Add support for configurable footer link in config
- Add permissions filters on resource view and index
- Add permissions contain options on resource view

### Chores
- Update OpenPGP-PHP dependencies to provide PHP 7.4 compatibility
- Remove unmaintained user agent parser library
- Fix PHP 7.4 warnings

### Improvements
- Improve testsuite execution times
- Refactor testsuite to not install data model from fixtures but use migrations instead
- Refactor testsuite to remove unused fixtures
- Migrate administration and mfa settings screen to React
- Add placeholder application skeleton when webextension is still loading
- Redesign of login and recover screens
- Add Mysql 8 support

## [2.13.5] 2020-07-30
### Fixed
- Fix allow overriding rememberMe options in passbolt.php configuration file
- Fix all target blank link should contain rel noopener noreferrer
- Fix email sender, email subject should not exceed 255 characters.
- Fix secret access log on resource view with contain secret
- GITHUB-376 Fix missing route prefix on the recovery button
- GITHUB-373 Fix API format for create group (previously v1 instead of v2 format)
- GITHUB-372 Fix after modifying passwd, the modification time should be changed
- GITHUB-370 Fix metadata should be deleted for deleted resources
- GITHUB-369 Fix Notification Emails Have Wrong Tense In Subject/Body
- GITHUB-368 Clarify PHP extension requirements
- GITHUB-362 Fix wrong filename on healthcheck HELP message for assertConfigFiles
- GITHUB-356 As a user I shouldn't be able to export folders if export plugin is disabled
- GITHUB-350 Fix no mails are sent when providers offer AUTH PLAIN authentication only
- GITHUB-339 Fix web installer urls do not work when passbolt is installed in a directory
- Fix performance issues on resource / folder activity log

## [2.13.5] 2019-07-29
### Fixed
- Fix display a validation error when db password contains a quote or db name contain a dash
- Fix email notification settings bootstrap messes up non persistent database connection in wizard
- Bump dependencies versions

## [2.13.1] 2020-07-06
### Fixed
- PB-1372 Fix user setup completed admin email notification

## [2.13.0] 2020-06-23
### Added
- PB-1168 Add baseline code and tests for Debian package build
- PB-1067 As a user I can receive digest emails when creating a lot of resources
- PB-1067 As a user I can receive digest emails when added/removed from a lot of groups
- PB-1284 Add tasks and services to re-validate existing data

### Improved
- Pro Styleguide version bump v2.13.13
- Appjs version bump v2.13.7
- PB-1046 Adapt Cleanup test runner to take in account cleanup that are adding records
- PB-1046 Adapt Cleanup shell task to allow external sources to add cleanup tasks
- PB-1046 Remove empty EmailTraits files
- Delete unused default keys (cleanup)
- Update to latest passbolt_test_data version.
- Misc refactoring for email notifications
- Misc refactoring to split model logic into services
- Clear plugins in tearDown of application test cases

### Fixed
- GITHUB-350 No mails are sent when providers offer AUTH PLAIN authentication only
- Fix appjs plugin requestUntilSuccess bug
- Fix load webinstaller plugin manually in plugin tests
- Fix composer php version.
- Fix misc checkstyle issues
- PB-980: Fix "secret access logging in password activity log should not display other resources secret access after a multiple share"

## [2.12.1] - 2020-04-14
### Security fixes
- PB-1209: Update javascript client dependencies

## [2.12.0] - 2019-12-06
### Added
- PB-687: As an admin I can resend an invitation for a user that didn't complete the setup

### Improved
- PB-878: Update Openpgp.js to v4.7
- PB-893: Update CakePHP to v3.8.6

### Fixed
- PB-771: Added purify subject for the email subscribers
- PB-856: Added migration fix to remove unused tables
- GITHUB-84: Fix gc_maxlifetime versus Session.timeout units

## [2.11.0] - 2019-08-08
### Security fixes
- PB-661: Fix tab nabbing when clicking on "open in a new tab" in password grid
- PB-607: Fix XSS on first name or last name during setup

### Improvements
- PB-587: Add baseline support for multiple openpgp backends
- PB-391: Display the name and email of the user an admin is going to delete in the delete dialog
- PB-396: Display the label of the password a user is going to delete in the delete dialog
- PB-397: Display a relevant feedback in the user details group section if the user is not member of any group
- PB-533: Add a new session check endpoint that does not extend the session expiry
- PB-607: Add option for an administrator to configure CSP using environment variable
- PB-242: Improve the passwords grid (passwords fetch peformance, search reactivity, selectbox area enlarged)

### Fixes
- PB-349: Fix health check fails if using custom GNUPGHOME env set by application
- PB-330: Fix migration issue from CE to PRO in v2.10
- PB-567: Fix appjs auto logout
- PB-601: Fix some incomplete unit tests
- PB-427: Fix email sender shell task and organization settings table unnecessary coupling
- PB-349: Fix OpenPGP results health checks

### Maintenance
- PB-505: Upgrade cake 3.8
- PB-504: Upgrade Javascript dependencies
- PB-472: Cleanup test dependencies

## [2.10.0] - 2019-05-15
### Added
- PB-165: As AD I should be able to change my organization email notification settings via an administration screen

### Improved
- PB-276: Merge organization settings code into CE. Ground work for administration features.

## [2.9.0] - 2019-04-24
### Fixed
- PB-220: Upgrade to CakePHP 3.7.7

## [2.8.4] - 2019-04-17
### Improved
- PB-48: Improve the performance by removing the creator/modifier from the passwords workspace grid query
- PB-159: Remove the usage of canjs connect-hydrate module

### Fixed
- GITHUB-315: The permalink of password don't work anymore
- PB-147: Update appjs steal dependencies
- PB-152: The webinstaller should work with Firefox ESR
- GITHUB-299: The passwords are shown twice in passwords workspace grid
- GITHUB-10: Selecting a group on the users workspace should not reset the grid "Last Logged In" column to "Never"
- GITHUB-62: Sorting the users on the users workspace should not break the infinite scroll
- PB-160: Update appjs jquery dependencies
- PB-163: Update jquery dependency
- PB-171: Fix entities history trait should not trigger internal error if user action is undefined
- PB-102: Fix install process should not create shema dump lock file
- PB-204: Escape shell variables of the passbolt mysql export shell command

## [2.8.3] - 2019-04-02
### Fixed
- PB-101: Fix version number
- PB-104: Implement enable / disable config switch for import export in default config

## [2.8.2] - 2019-04-01
### Fixed
- Fix - Disable Auditlog when passbolt is not configured

## [2.8.1] - 2019-04-01
### Fixed
- Remove PassboltTestData dev tool call from PassboltShell

## [2.8.0] - 2019-04-01
### Added
- Import your passwords from other password managers
- Export your passwords to other password managers
- PB-3: Quickaccess: Simplified app to access passwords from the browser extension

### Improved
- PB-2: Upgrade to CakePHP 3.7
- PB-95: Implement Import / Export enable switch

### Fixed
- PASSBOLT-2121: Fix passbolt should run in a subdirectory
- Fix short tag use in the webinstaller server gpg key import screen
- Username and password should not be compulsory in email settings, in web installer

## [2.7.1] - 2019-02-13
### Fixed
- PASSBOLT-3416: Fix the uses of php shortags in the webinstaller template files

## [2.7.0] - 2019-02-12
### Added
- PASSBOLT-2995: As LU I should be able to copy the permalink of a password

### Improved
- PASSBOLT-3403: As LU I should export only selected passwords
- PASSBOLT-3397: Remove the list of secrets from the API request while loading the list of passwords
- PASSBOLT-3319: As LU I should retrieve a secret when I'm editing it
- PASSBOLT-3318: As LU I should retrieve a secret when I'm copying it
- PASSBOLT-3317: Display significant information as soon as possible while opening the application
- PASSBOLT-3312: As GM adding a user to a group I should see a relevant feedback in case of network/proxy errors
- PASSBOLT-3314: Improve the performance of the application by adding missing indexes
- PASSBOLT-2974: As LU I should be able to follow links targeting passwords from my emails

### Fixed
- PASSBOLT-3363: The webinstaller should not use the exec php primitive to create/import the gpg server key
- PASSBOLT-3370: Auth verify error should not leak data
- PASSBOLT-3368 Fix html injection in email

## [2.5.0] - 2018-11-14
### Added
- PASSBOLT-2694: Implement the Web Installer feature
- PASSBOLT-3093: As LU I can select all passwords to perform a bulk operation

### Improved
- PASSBOLT-3166: Add PHP 7.3 job on travis
- PASSBOLT-3119: The Web Installer should control the route with a middleware
- PASSBOLT-3153: The Web Installer healtchecks should ensure the config files can be written before continuing
- PASSBOLT-3120: Improve the Web Installer code coverage
- PASSBOLT-3127: The Web Installer should change the config folder permissions after the installation is completed
- PASSBOLT-3152: As AN completing the registration process, if I'm following the link to download the browser extension I cannot go back easily to the registration process
- PASSBOLT-3189: As AD migrating passbolt to the latest version I would like the CakePHP cache to be cleared with the same operation

### Fixed
- PASSBOLT-3150: I should not see duplicates rows when I filter my passwords by keywords
- GITHUB-290: A user who have not completed the setup should be allowed to request a new token using recover
- PASSBOLT-3188: As LU the UI shouldn't crash if the uri of a password cannot be parsed

## [2.4.0] - 2018-10-12
### Added
- PASSBOLT-2709: Implement the remember me feature
- PASSBOLT-2951: Merge the remember me on CE
- PASSBOLT-2972: As LU I should be able to delete multiple passwords in bulk
- PASSBOLT-2329: As an administrator deleting a group which is sole owner of one or several passwords, I should be requested to select a new owner for these passwords
- PASSBOLT-2983: As LU I should be able to share multiple passwords in bulk

### Improved
- PASSBOLT-3009: Add types to authentication tokens
- GITHUB#275: Adding SSL configuration environment variables for cake mysql driver
- PASSBOLT-2534: As LU I should not be able to copy to clipboard empty login/url
- PASSBOLT-2017: As LU when I save a password (create/edit) the dialog shouldn't persist until the request is processed by the API
- PASSBOLT-3073: As LU I should get a visual feedback directly after filtering the passwords or the users workspace
- PASSBOLT-2972: As LU I should be able to select multiple passwords with classic keyboard interactions (command and shift keys)
- PASSBOLT-3090: Extend the CSRF protection

### Fixed
- PASSBOLT-2966: As LU I can't see passwords shared with me clicking on the "shared with me" shortcut filter
- GITHUB#246: Fix healthcheck tips relative to tmp folder
- Fix Email notifications being sent several times when an AppShell is instantiated inside an AppShell
- PASSBOLT-3063: Fix appjs base url and subfolder
- PASSBOLT-3074: As a logged in user selecting a "remember me" duration the  checkbox should be selected automatically
- PASSBOLT-2976: Fix API requests issues when the user is going to another workspace
- PASSBOLT-3082: ezyang/htmlpurifier cache should be stored in the application cache directory
- PASSBOLT-2982: Fix session expired check
- PASSBOLT-3086: As LU when I have 100+ passwords I cannot see the passwords after the 100th more than once

## [2.3.0] - 2018-08-30
### Fixed
- PASSBOLT-2965: Group filter link stays active after switching to a non group filter
- Route rewriting of the appjs should take in account passbolt installed in a subdirectory
- Fix the loading bar stuck in the initialization state in some cases
- PASSBOLT-2969: Enforce steal to load the latest version of the appjs

### Improved
- PASSBOLT-2950: Display empty feedbacks content
- PASSBOLT-2971: Reset the workspaces filters when a resource or a user is created
- PASSBOLT-2267: As an admin deleting a user I can transfer ownership of this user shared passwords to another user or a group that have read access.

## [2.2.0] - 2018-08-13
### Added
- PASSBOLT-2906: Enable CSRF protection
- PASSBOLT-2940: Implement app-js primary routes

### Fixed
- PASSBOLT-2805: Sort by date fix and sort by user first_name by default
- PASSBOLT-2896: Fix filter by tag from the password details sidebar
- PASSBOLT-2903: Fix logout link. It should target a full based url link
- PASSBOLT-2926: Fix session timeout check
- PASSBOLT-2927: Fix appjs ajax error handler
- PASSBOLT-2941: Grid performance fix

### Improved
- PASSBOLT-2933: Upgrade to canjs 4

## [2.1.0] - 2018-06-14
### Added
- PASSBOLT-2878: Integrate dark theme
- PASSBOLT-2861: Make username clickable for copy to clipboard

### Fixed
- GITHUB-101: Fix the readme should point to the documentation for how to upgrade passbolt
- PASSBOLT-2682: Fix healthcheck entry point when logged in as admin and debug is false
- PASSBOLT-2869: Fix GPG wrapper should recognize the correct type and bit length
- PASSBOLT-1917: Migrate to canjs 3.x
- PASSBOLT-2883: Fix logout link should not prevent event propagation
- PASSBOLT-2886: Fix fingerprint tooltips in user group management dialog
- PASSBOLT-2894: Fix missing div breaking elipsis on long url in password workspace
- PASSBOLT-2891: Fix group edit users tooltips
- PASSBOLT-2884: Update header left menu. Remove home and add help.
- PASSBOLT-2885: Update user settings menus
- PASSBOLT-2895: Fix notifications homogeneity
- PASSBOLT-1337: Fix a logged in user should not be allowed to login or recover
- PASSBOLT-1337: Remove gpg json sign middleware
- PASSBOLT-1337: Wordsmithing healthcheck GPG feedback

## [2.0.7] - 2018-05-09
### Fixed
- Fix missing css on error pages and add version numbers to CSS and JS files calls to prevent caching
- Fix do not enable debugKit when debug is set to true

## [2.0.5] - 2018-05-08
### Fixed
- PASSBOLT-2764: Fix Groups autocomplete doesn't work with less than 3 characters
- PASSBOLT-2826: Upgrade styleguide to v2.1.0
- PASSBOLT-2812: Rebuild fixtures with updated public keys

## [2.0.4] - 2018-04-25
### Fixed
- COMMUNITY-599: Make email MX validation optional and not enabled by default
- GITHUB-247: Fix secrets are not deleted when deleting a group or a user

## [2.0.3] - 2018-04-20
### Fixed
- PASSBOLT-2849: Fix issue ResourcesTable::_filterByPermissionType and MariaDB 5.5
- PASSBOLT-2848: Fix unsafe mode and ssl offloading

## [2.0.2] - 2018-04-16
### Improved
- GITHUB-242: Add Auto-Submitted header to the email notifications

### Fixed
- PASSBOLT-2806: Force database columns charset and collation
- PASSBOLT-2781: Increase length of resource uri field in model validation
- PASSBOLT-2696: Fix regression: placeholders in registration form are missing
- PASSBOLT-2791: Fix providing a string instead of an array in Email. From configuration generates a warning in SendTestEmailTask.php

## [2.0.1] - 2018-04-09
### Fixed
- GITHUB-239: Fix unsafe mode logic
- GITHUB-240: Make sure unconfigured 'passbolt.plugins' doesn't break the extension
- PASSBOLT-2511: Improve healthcheck tables list so that tables are listed per major version number

## [2.0.0] - 2018-04-09
### Added
- PASSBOLT-2725: Implement start page when passbolt is not configured
- PASSBOLT-2740: Update <3 link and add unsafe mode warning
- PASSBOLT-2697: Add passbolt migrate shell with backup option prior migration
- PASSBOLT-2803: Make the privacy policy footer link configurable in the settings
- PASSBOLT-2720 Move dev dependencies out of the passbolt_api repo
- PASSBOLT-2511: passbolt pro bootstrap is moved in a separate folder

### Fixed
- GITHUB-229: Fix passbolt can not run in a subdirectory
- COMMUNITY-533: Fix plaintext should be initialized prior verification
- PASSBOLT-2776: Fix: As AN, settings entry point should be able to have plugins settings whitelisted
- PASSBOLT-2762: Fix unexpected error on resource share
- PASSBOLT-2754: Change the way to define if passbolt is installed while running the unit tests
- PASSBOLT-2571: Delete secrets when a password is soft deleted
- PASSBOLT-2688: Fix healtcheck warning if the development plugin passbolt_test_data is not loaded
- PASSBOLT-2711: Delete orphans secrets
- PASSBOLT-2678: Edit Appjs API calls to use version number
- PASSBOLT-2694: Improve GPG lib to handle private keys validation
- PASSBOLT-2744: Favorites delete on group user delete
- PASSBOLT-2743: Favorites delete on permissions update
- PASSBOLT-2705: Increase coverage, ensure all users who lost access to a resource have no a secret in db for this resource
- PASSBOLT-2735: Display a specific message if a sidebar section has not content to display
- PASSBOLT-2664: Change cakephpConfig into settings entry point and adjusted app-js to work with it

## [2.0.0-rc2] - 2018-02-20
### Added
- PASSBOLT-2638: Added command to test email configuration and SMTP communication
- PASSBOLT-2608: Implement Sidebar v2 in the Appjs
- PASSBOLT-2660: Add codacy badge
- PASSBOLT-1741: Add more GPG healthchecks
- PASSBOLT-1741: Add PHP extension checks to the healthcheck
- PASSBOLT-2597: Add check before upgrade to ensure passbolt is already in latest 1.x
- PASSBOLT-2631: Add an env var to control which email transport to use and defaults to Smtp
- PASSBOLT-2601: Add Travis v2: phpunit, coverage, phpcs

### Fixed
- PASSBOLT-2618: Fixes for PHP 7.2 compatibility
- PASSBOLT-2624: PR#219 Fixed use CONFIG instead of "ROOT . DS . 'config'"
- PASSBOLT-2631: Fixed default class for EmailTransport to Smtp in configuration
- PASSBOLT-2640: Fixed incomplete urls in email templates
- PASSBOLT-2640: Fixed escaping of non safe characters in emails
- PASSBOLT-2667: Fixed regression: create a user that has been deleted previously returns an error
- PASSBOLT-2673: Fixed regression: as AD I cannot create a group with the name of previously deleted group
- PASSBOLT-2545: Fixed regression: As AD deleting a group I should be notified that all members of the group gonna lose access to the passwords shared with the group
- PASSBOLT-2139: Fixed check sessions calls are logged as error
- PASSBOLT-2139: Fixed not found image on password workspace
- PASSBOLT-1741: Fixed set license to AGPL-3.0-or-later for composer compatibility
- PASSBOLT-2589: Fixed App-js should check request response code from the http response header and not from the body header
- PASSBOLT-2533: Fixed resource name, username, uri, description min length should be 1 char not 3
- PASSBOLT-2660: Fixed remove flash message from login layout

## [2.0.0-rc1] - 2018-01-12
### Security
- XSS protection improvements, with a new test suite dedicated for XSS.
- HTTP security headers are enabled by default and can be disabled using configuration options.
- Json responses server signature (experimental).

### Improved
- An expired setup link can be re-sent through the recovery procedure.
- Dropped SQL views (will allow supporting additional database backends).
- Simplified configuration system. The entire configuration will be done in one dedicated file with safer defaults.
- Most configuration items are now available as environment variables.
- Install commands perform additional health checks prior to running.
- CakePHP and other dependencies have been removed from the repository and are now installed with composer.
- More flexible validation rules for inputs in most fields.
- Emojis support where it make sense (comments, descriptions, etc).
- Some notifications will not be sent if the user is the one doing the action (ex. delete password).
- The App-JS code is now available on a dedicated repository.
- Misc javascript foundation code refactoring.
- Added missing tables index to speed up some database queries.
- “Owner” has been replaced by “Created by” in the password sidebar to be more relevant.
- API supports a more standard response format (documentation coming soon).
- Additional settings for controlling what is displayed in email notifications.
- Added created date information in password sidebar.

### Changed
- Passbolt api migration to CakePHP 3.
- PHP 7.0 is now the minimum supported version.
- Dropped table “controller_logs”. It will be soon replaced by the Audit Logs feature.
- Dropped table “schema_migrations”.
- Dropped table “cake_sessions”.
- Dropped “anonymous statistics” feature (nobody opted in…).

### Fixed
- “Passwords I own” filter displays all the passwords for which I have “is owner” permission.
- An admin can delete a user if the user is the sole group member of a group owning passwords that are not shared.
- An admin can delete a user if the user is the sole owner of a password that is not shared.

## [1.6.9] - 2018-01-12
### Fixed
- PASSBOLT-2599: PR#209: Expose the 'client' variable in the default email conf
- PASSBOLT-2599: PR#211: Remove stray apostrophe in the filter by group component
- PASSBOLT-2599: PR#214: Remove html purifier submodule
- PASSBOLT-2599: PR#208: Fix typos in emails
- PASSBOLT-2599: PR#159: Rename license file
- PASSBOLT-2599 Fixed Travis
- PASSBOLT-1453: Add optional predictable UUID for auth token in selenium testing
- PASSBOLT-2474 New contributing guidelines for community forum

## [1.6.5] - 2017-09-12
### Added
- PASSBOLT-2383: Add + and \ to the list of allowed characters for the Resource fields: name, username and description

### Fixed
- PASSBOLT-2371: Force the charset of the cake_sessions table in utf8
- PASSBOLT-2325: As system administrator I shouldn't be able to execute passbolt CLI commands as root
- PASSBOLT-2397: As system administrator I should see in the healthcheck if app/tmp content and app/webroot/img/public content are writable
- PASSBOLT-1991: As system administrator I should see in the healthcheck if the server key can be used for encrypting/decrypting

### Security
- PASSBOLT-2409: Noopener on resource url in password workspace
- PASSBOLT-2402: XSS on resource url in password workspace

## [1.6.4] - 2017-08-31
### Fixed
- PASSBOLT-2358: As a user registering on the demo instance I must understand the disclaimer

## [1.6.3] - 2017-08-21
### Fixed
- PASSBOLT-2316: Merge the selenium & phpunit dummy data sets
- PASSBOLT-2317: Speed up dummy secret creation task
- PASSBOLT-2327: Add a large set of dummy data for performance testing
- PASSBOLT-2282: As admin on the user workspace, I should be able to distinguish visually the users who haven't activated their account yet

## [1.6.2] - 2017-08-12
### Added
- PASSBOLT-2284: As an administrator I can set which notifications are enabled for my organization #98
- PASSBOLT-2284: As an administrator I can prevent encrypted secret or username to be sent in email notification #114

### Fixed
- PASSBOLT-2301: Remove additional slashes in passbolt.js urls such as model/users::find #142
- PASSBOLT-2270: Fix modified_by not set on resource edit regression
- PASSBOLT-2271: Fix no wrap issue on resource description
- PASSBOLT-1943: As an administrator I should not be able to install passbolt on a hostname that is not RFC3986 compliant
- PASSBOLT-1937: As an administrator I should not be be able to install passbolt with a server key without an email id
- PASSBOLT-2002: Refactor install script to reuse healthcheck library

## [1.6.1] - 2017-07-26
### Added
- PASSBOLT-2147: As a group member I should receive a notification when my role in the group has changed
- PASSBOLT-2148: As a group manager I should receive a notification when a user who is part of one (or more) groups I manage is deleted
- PASSBOLT-2225: As a demo user it should be explicit that I need to use a throway email account
- PASSBOLT-2133: As LU I should be able to filter passwords by group on the passwords workspace
- PASSBOLT-2012: As a user I can see which groups a user is a member of from the sidebar

### Fixed
- PASSBOLT-2171: The group list component should be marked as ready once the API request is completed
- PASSBOLT-2172: Newly added group manager shouldn't receive the group update summary notification
- PASSBOLT-2174: Edit group dialog should be marked as ready if an admin edit a group the admin is not group manager
- PASSBOLT-2155: As AD I shouldn't be able to delete as user if the user is the sole group manager of a group
- PASSBOLT-2075: Users should be removed from the groups they are member of after a soft delete operation
- PASSBOLT-1934: GITHUB-40, GITHUB-120: As a user I should be allowed to add the a ldap path as username
- PASSBOLT-2156: GITHUB-94: As a user I should be allowed to add text in JSON format in the description
- PASSBOLT-2122: GITHUB-85: Username should be Minimum 1 characters in length (and not 3)
- PASSBOLT-2180: GITHUB-85: As a user I should be allowed to add a space in a resource username
- PASSBOLT-2125: GITHUB-86: As a logged in user creating/editing a password I should be able to use new line characters in the description
- PASSBOLT-2188: Regression: As LU when I search for a user it shouldn't make an API request
- PASSBOLT-2234: Regression: As newly added GM I shouldn't receive the group update summary when I'm just added as GM
- PASSBOLT-2235: As AD editing a group the dialog shouldn't be marked as ready until the members list is loaded
- PASSBOLT-2105: Anonymous statistics: fix "Warning Error: file_put_contents" issue at installation
- PASSBOLT-2005: PR#44: Update allowed characters in a uri

## [1.6.0] - 2017-06-21
### Added
- PASSBOLT-2099: As a user I should receive a notification when I am added to a group
- PASSBOLT-2100: As a user I should receive a notification when I am deleted of a group
- PASSBOLT-2102: As a group manager I should receive a notification when another group manager added a user to a group I manage
- PASSBOLT-2103: As a group manager I should receive a notification when another group manager removed a user from a group I manage
- PASSBOLT-2140: As a group manager I should receive a notification when another group manager changed the role of a user of a group I manage
- PASSBOLT-2138: The TLS parameter should be part of the default email configuration

### Fixed
- PASSBOLT-2044: As an admin I shouldn’t be able to delete a user who is the sole owner of passwords shared with others
- PASSBOLT-2078: As GM/AD I shouldn't be able to add a user who didn't complete the registration process to a group I edit/create
- PASSBOLT-2111: As an admin I should be able to install passbolt under mydomain.tld/passbolt
- PASSBOLT-2142: As an admin I should not see multiple ASCII banner when running the install script
- PASSBOLT-1959: As LU when I unshare a password with a user or a group, associated secrets should be destroyed
- PASSBOLT-1954: Security: Trackable behavior should override created_by and deleted_by when provided

## [1.5.1] - 2017-05-23
### Fixed
- PASSBOLT-2070: Delete unused code / exclude external libs from coverage
- PASSBOLT-2071: Drop exec bits from files which don't need them (@OdyX GITHUB PR #67)
- PASSBOLT-2073: As AP I should see a warning on the login page if the plugin and the api are not compatible
- PASSBOLT-2029: PHP7 compatibility, fix deprecated cakePHP String class calls (@leomazzo GITHUB-64)
- PASSBOLT-2074: Delete confirmation dialogs should fit the latest styleguide

## [1.5.0] - 2017-05-16
### Added
- PASSBOLT-1950: As a user I can see which groups a password is shared with from the sidebar
- PASSBOLT-1953: As a user I can share a password with a group
- PASSBOLT-1940: As a user when editing a password for a group, the secret should be encrypted for all the members
- PASSBOLT-1639: As a user editing a password description in the right sidebar should not get duplicated items in shared with section
- PASSBOLT-1938: As a user I can browse the list of groups in the groups section of the user workspace
- PASSBOLT-2000: As a user I can see which users are part of a given group from the sidebar and the users section
- PASSBOLT-1960: As a user I can see the list of users that are part of the group in the users grid by using the group filter
- PASSBOLT-1838: As a group manager I can edit the membership roles
- PASSBOLT-1838: As a group manager I can add a user to a group
- PASSBOLT-1838: As a group manager I can remove a user from a group using the edit group dialog
- PASSBOLT-1969: As a group manager I can edit a group from the contextual menu and from the groups sidebar
- PASSBOLT-1969: As a group manager I can see which users are part of a given group from the group edit dialog
- PASSBOLT-2000: As a group manager I can see which users are part of a given group from the sidebar and the users section
- PASSBOLT-2006: As an administrator I can delete a group from the group contextual menu
- PASSBOLT-1969: As an administrator I can edit a group
- PASSBOLT-2006: As an administrator I can delete a group
- PASSBOLT-1955: As an administrator I can create a group using the new button in the users workspace
- PASSBOLT-1939: As an administrator the healthcheck should be accessible in command line
- PASSBOLT-1943: As an administrator the healthcheck should tell if not using a proper domain name as base url
- PASSBOLT-1943: As an administrator the healthcheck should tell if SSL certificate is invalid
- PASSBOLT-1885: As an administrator the healthcheck should tell if the full base url is not reachable
- PASSBOLT-1838: Add v1.5.0 migration script
- PASSBOLT-1881: Add support for groups in the permission system
- PASSBOLT-1952: Add support for groups in the fixtures
- PASSBOLT-1928: Deploy styleguide with groups support

### Fixed
- PASSBOLT-1614: Abstract user/password grid functions into the mad framework grid library
- PASSBOLT-1571: API query string filters: better naming conventions and implementation
- PASSBOLT-1915: Remove legacy references related to old user passwords
- PASSBOLT-1761: Remove legacy references to throttle login
- PASSBOLT-1268: Remove legacy dictionary controller
- PASSBOLT-1268: Use exceptions instead of message component errors and misc refactoring
- PASSBOLT-2036: Fix travis database configuration issue
- PASSBOLT-2037: Schema should allow resources fields username and uri to be null
- PASSBOLT-2038: Travis and php54

## [1.4.0] - 2017-02-07
### Fixed
- PASSBOLT-1863: Remove references to legacy features Category and Tags
- PASSBOLT-1883: Fix wrong usage of the permission entry point viewByAco
- PASSBOLT-1887: Remove the entry point PermissionController::simulateAcoPermissionsAfterChange
- PASSBOLT-1886: Remove the controller component PermissionHelperComponent
- PASSBOLT-1888: Remove the model behavior function PermissionableBehavior::getUsersWithAPermissionSet
- PASSBOLT-1889: Remove references to legacy models and tables (AuthenticationLogs, AuthenticationBlackList, Email, Adress, PhoneNumber)
- PASSBOLT-1890: Clean the Permission model validation functions & augment coverage
- PASSBOLT-1894: Reorganize ACL models tests
- PASSBOLT-1896: Remove references to legacy permission types CREATE and DENY
- PASSBOLT-1511: removed tracking of config file Config/email.php (@BaumannMisys GITHUB-34)
- PASSBOLT-1835: As a user I should be able to create an account with the same username as an account that was previously deleted (@bestlibre GITHUB-33)
- PASSBOLT-1646: GITHUB-20 Permissions views and queries do not work with Mysql57 / only_full_group_by enabled

## [1.3.2] - 2017-01-16
### Fixed
- PASSBOLT-811: Error message look and feel is not consistent on register / recover

## [1.3.1] - 2017-01-03
### Fixed
- PASSBOLT-1758: As LU sharing a password I should be able to filter users based on first name and last name
- PASSBOLT-1779: Remove debug statement
- PASSBOLT-1585: As AN I should be allowed to register if my lastname or firstname are 2 chars in length
- PASSBOLT-1783: Form validation and translation: malformed error messages
- PASSBOLT-1619: As AP I should not be allowed to recover my account if I have not completed the setup first
- PASSBOLT-1767: As a AD installing passbolt I should be told if webroot/img/public is not writable.
- PASSBOLT-1793: Upgrade to CakePHP v2.9.4
- PASSBOLT-1784: GITHUB-29 PHP7 compatibility issue in migration console tasks
- PASSBOLT-1790: Fixed update context sent by anonymous usage statistics

## [1.3.0] - 2016-11-25
### Fixed
- PASSBOLT-1721: SSL detection not working in healthcheck
- PASSBOLT-1708: Accept JSON data content type for HTTP PUT during setup

### Added
- PASSBOLT-1725: Misc changes for Chrome support
- PASSBOLT-1726: Implement anonymous usage data

## [1.2.1] - 2016-10-19
### Fixed
- PASSBOLT-1719: GITHUB-14 The "." is not allowed in email address field
- PASSBOLT-1525: Remove unused controllers and components
- PASSBOLT-1718: Tidy up readme and contribution guidelines

## [1.2.0] - 2016-10-17
### Added
- PASSBOLT-1706: GITHUB-18 Resource Description length is too short, should be 10K characters
- PASSBOLT-1658: GITHUB-18 Resource URI length is too short, should be 1024 characters
- PASSBOLT-1637: GITHUB-14 The "+" is not allowed in the email address field while adding a new user
- PASSBOLT-1525: Test coverage for SetupControllerTest & CakeErrorController
- PASSBOLT-1694: Default config change: debug should be set to 0
- PASSBOLT-1660: Refactoring to simplify Chrome plugin development
- PASSBOLT-1649: Adjusted coveralls markup
- PASSBOLT-1648: Upgrade to Cakephp 2.9.1
- PASSBOLT-1250: Contribution guidelines

### Fixed
- PASSBOLT-1700: Event names should stay backward compatible
- PASSBOLT-1668: Remove GPGAuth debug count
- PASSBOLT-1673: Restore avatars during quick install

## [1.1.0] - 2016-08-09
### Added
- PASSBOLT-1124: As LU on user workspace I should be able to see the last logged in date of a user.
- PASSBOLT-1216: As LU I should be able to sort the tableview in passwords workspace
- PASSBOLT-1217: As LU I should be able to sort the tableview in users workspace.
- PASSBOLT-1535: Fix mysql 5.7 schema issues and improve compatibility.
- PASSBOLT-1633: Travis and Coveralls integration.
- PASSBOLT-1597: Implemented schema versioning and migration tool.

### Fixed
- PASSBOLT-1604: As a AD I should be able to see the healthcheck page when debug is set to 0
- PASSBOLT-1525: Misc unit test code coverage & phpcs cleanup
- PASSBOLT-1653: After migration, Gpgkey.uid should be sanitized in DB.
- PASSBOLT-1634: Authentication logs are moved in each authentication stage.
- PASSBOLT-1383: Cleanup cakephp config & prevent future regressions like PASSBOLT-1621 with a default.
- PASSBOLT-1486: After deleting a user, I should be able to recreate a user with the same username.
- PASSBOLT-1620: Duplicate users in the list when selecting a user and using filters.
- PASSBOLT-1652: As LU I cannot use passbolt with long public key.

### Tests
- PASSBOLT-1642: Increased selenium tests coverage when browser is restarted.
- PASSBOLT-1643: Increased selenium tests coverage when passbolt tab is closed and restored.


## [1.0.14] - 2016-07-06
### Fixed
- PASSBOLT-1616: Fixed bad merge during the previous release.
- PASSBOLT-1599: GITHUB-10 passbolt.js requesting wrong path for config.json.

## [1.0.13] - 2016-06-30
### Fixed
- PASSBOLT-1605: Set::extract to Hash::extract refactoring regression.
- PASSBOLT-1601: ControllerLog Model should support IVP6 addresses.
- PASSBOLT-1366: Worker bug when multiple passbolt instances are open in multiple windows.
- PASSBOLT-1590: Styleguide bump to v1.0.38.
- PASSBOLT-1613: As a user losing access to a password I selected, I shouldn't encounter an error.
- PASSBOLT-1569: Cleanup: remove SetupController::ping.

### Added
- PASSBOLT-1077: As a LU searching for a password (or a user) search results should filter as I type.
- PASSBOLT-1588: As AN it should be possible to recover a passbolt account on a new device.

## [1.0.12] - 2016-05-31
### Fixed
- PASSBOLT-1439: Email is sent as anonymous when a user is created from the console.
- PASSBOLT-1509: As LU, when a password is shared with me in read only, I should not see the delete menu available in more.
- PASSBOLT-1407: As LU, there is no visual feedback when I upload a picture and that the process is in progress.
- PASSBOLT-1579: Segfault at the end of setup when trying to display login form.
- PASSBOLT-1576: Fixed Hash component warning message in EmailQueue.
- PASSBOLT-1322: Insertion of comments in unittest dataset display an error in the console.
- PASSBOLT-1234: Authentication token used for account registration expiracy check.

### Added
- PASSBOLT-1572: As LU, I should be able to see which users a password is shared with directly from the sidebar.

## [1.0.11] - 2016-05-16
### Added
- PASSBOLT-1388: As a user I should receive an email notification when a password is updated.
- PASSBOLT-1389: As a user I should receive an email notification when a password is created.
- PASSBOLT-1390: As a user I should receive an email notification when a password is deleted.
- PASSBOLT-1544: As a user I should receive an email notification when someone comments on a password.
- PASSBOLT-1221: API documentation with Swagger (Part I: models).

### Fixed
- PASSBOLT-1094: Frontend : Server errors happening during a request should give a visual feedback.
- PASSBOLT-1438: Retry button is not working at setup first step (when user doesn't have the plugin installed).
- PASSBOLT-1564: As a sysop, installing passbolt with quiet mode should not output any information.
- PASSBOLT-1434: Wordsmithing: rename master password to passphrase.

## [1.0.10] - 2016-05-03
### Fixed
- PASSBOLT-1502: String is depracated in Cakephp since version 2.7 use CakeText instead.
- PASSBOLT-1466: GET /auth/verify.json Content-Type should not be text/html but JSON.
- PASSBOLT-1443: Copy to clipboard icon is misleading

### Changed
- PASSBOLT-1419: Cleanup config.json for js client and remove useless config.
- PASSBOLT-1514: By default passbolt app should not be indexed by search engines.
- PASSBOLT-1474: API: Upgrade cakephp to 2.8.3.
- PASSBOLT-1288: As an AD during install I should have status page to help me.

## [1.0.9] - 2016-04-25
### Fixed
- PASSBOLT-1505: As AP, I should not get an error during setup if my key has been generated on a system that is not exactly on time.
- PASSBOLT-1457: As LU, I should not be able to create a resource without password.
- PASSBOLT-1441: Wordsmithing: a parenthesis is missing on set a security token step.
- PASSBOLT-1158: Remove all errors (plugin/client) from the browser console at passbolt start.

### Changed
- PASSBOLT-1456: When generating a password automatically it only generates a "fair" level password.
- PASSBOLT-1495: Passbolt: update installation instructions in README file.

## [1.0.8] - 2016-04-15
### Fixed
- PASSBOLT-1445: As a LU viewing someone else comment I should not see the delete comment button.
- PASSBOLT-1402: As LU, In the comment thread I should not see a hyperlink on people's name that leads to nowhere.

## [1.0.7] - 2016-04-04
### Added
- PASSBOLT-1223: Implemented state for empty password workspace.

### Changed
- PASSBOLT-1450: Change information button icon. Eye becomes information.

## [1.0.6] - 2016-03-28
### Added
- PASSBOLT-1343: Confirmation email link opened in chrome does not explain that passbolt works only in firefox.
- PASSBOLT-1416: Improved coverage : API / Token should not be disabled when validateAccount fails.
- PASSBOLT-1444: Slack plugin for passbolt to keep track of demo registrations.

### Fixed
- PASSBOLT-1395: Regression : As LU I should not be able to select two password.
- PASSBOLT-1396: As LU I should not see a mix of two dashboards if I click quickly on the users and passwords menu links.
- PASSBOLT-1406: Space missing between first name and last name in registration email.

## [1.0.5] - 2016-03-21
### Added
- PASSBOLT-1384: Admin user should be registered during installation.
- PASSBOLT-1310: As user whose account is deleted I should get feedback on login.

### Fixed
- PASSBOLT-1415: Please register links are broken for AP.
- PASSBOLT-1157: An error page should not include any scripts.
- PASSBOLT-1243: I should see an error when I try to upload an avatar with a wrong file type / size

# Terminology
- AN: Anonymous user
- LU: Logged in user
- AP: User with plugin installed
- AD: Admin

[Unreleased]: https://github.com/passbolt/passbolt_api/compare/v4.2.0...HEAD
[4.2.0]: https://github.com/passbolt/passbolt_api/compare/v4.1.1...v4.2.0
[4.1.1]: https://github.com/passbolt/passbolt_api/compare/v4.1.0...v4.1.1
[4.1.0]: https://github.com/passbolt/passbolt_api/compare/v4.0.2...v4.1.0
[4.0.2]: https://github.com/passbolt/passbolt_api/compare/v4.0.1...v4.0.2
[4.0.1]: https://github.com/passbolt/passbolt_api/compare/v4.0.0...v4.0.1
[4.0.0]: https://github.com/passbolt/passbolt_api/compare/v3.12.2...v4.0.0
[3.12.2]: https://github.com/passbolt/passbolt_api/compare/v3.12.0...v3.12.2
[3.12.0]: https://github.com/passbolt/passbolt_api/compare/v3.11.1...v3.12.0
[3.11.1]: https://github.com/passbolt/passbolt_api/compare/v3.11.0...v3.11.1
[3.11.0]: https://github.com/passbolt/passbolt_api/compare/v3.10.0...v3.11.0
[3.10.0]: https://github.com/passbolt/passbolt_api/compare/v3.9.0...v3.10.0
[3.9.0]: https://github.com/passbolt/passbolt_api/compare/v3.8.3...v3.9.0
[3.8.3]: https://github.com/passbolt/passbolt_api/compare/v3.8.1...v3.8.3
[3.8.1]: https://github.com/passbolt/passbolt_api/compare/v3.8.0...v3.8.1
[3.8.0]: https://github.com/passbolt/passbolt_api/compare/v3.7.3...v3.8.0
[3.7.3]: https://github.com/passbolt/passbolt_api/compare/v3.7.2...v3.7.3
[3.7.2]: https://github.com/passbolt/passbolt_api/compare/v3.7.1...v3.7.2
[3.7.1]: https://github.com/passbolt/passbolt_api/compare/v3.7.0...v3.7.1
[3.7.0]: https://github.com/passbolt/passbolt_api/compare/v3.6.0...v3.7.0
[3.6.0]: https://github.com/passbolt/passbolt_api/compare/v3.5.0...v3.6.0
[3.5.0]: https://github.com/passbolt/passbolt_api/compare/v3.4.0...v3.5.0
[3.4.0]: https://github.com/passbolt/passbolt_api/compare/v3.3.1...v3.4.0
[3.3.1]: https://github.com/passbolt/passbolt_api/compare/v3.3.0...v3.3.1
[3.3.0]: https://github.com/passbolt/passbolt_api/compare/v3.2.1...v3.3.0
[3.2.1]: https://github.com/passbolt/passbolt_api/compare/v3.2.0...v3.2.1
[3.2.0]: https://github.com/passbolt/passbolt_api/compare/v3.1.0...v3.2.0
[3.1.0]: https://github.com/passbolt/passbolt_api/compare/v3.0.2...v3.1.0
[3.0.2]: https://github.com/passbolt/passbolt_api/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/passbolt/passbolt_api/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/passbolt/passbolt_api/compare/v2.13.5...v3.0.0
[2.13.5]: https://github.com/passbolt/passbolt_api/compare/v2.13.1...v2.13.5
[2.13.1]: https://github.com/passbolt/passbolt_api/compare/v2.13.0...v2.13.1
[2.13.0]: https://github.com/passbolt/passbolt_api/compare/v2.12.1...v2.13.0
[2.12.1]: https://github.com/passbolt/passbolt_api/compare/v2.12.0...v2.12.1
[2.12.0]: https://github.com/passbolt/passbolt_api/compare/v2.11.0...v2.12.0
[2.11.0]: https://github.com/passbolt/passbolt_api/compare/v2.10.0...v2.11.0
[2.10.0]: https://github.com/passbolt/passbolt_api/compare/v2.9.0...v2.10.0
[2.9.0]: https://github.com/passbolt/passbolt_api/compare/v2.8.4...v2.9.0
[2.8.4]: https://github.com/passbolt/passbolt_api/compare/v2.8.3...v2.8.4
[2.8.3]: https://github.com/passbolt/passbolt_api/compare/v2.8.2...v2.8.3
[2.8.2]: https://github.com/passbolt/passbolt_api/compare/v2.8.1...v2.8.2
[2.8.1]: https://github.com/passbolt/passbolt_api/compare/v2.8.0...v2.8.1
[2.8.0]: https://github.com/passbolt/passbolt_api/compare/v2.7.1...v2.8.0
[2.7.1]: https://github.com/passbolt/passbolt_api/compare/v2.7.0...v2.7.1
[2.7.0]: https://github.com/passbolt/passbolt_api/compare/v2.5.0...v2.7.0
[2.5.0]: https://github.com/passbolt/passbolt_api/compare/v2.4.0...v2.5.0
[2.4.0]: https://github.com/passbolt/passbolt_api/compare/v2.3.0...v2.4.0
[2.3.0]: https://github.com/passbolt/passbolt_api/compare/v2.2.0...v2.3.0
[2.2.0]: https://github.com/passbolt/passbolt_api/compare/v2.1.0...v2.2.0
[2.1.0]: https://github.com/passbolt/passbolt_api/compare/v2.0.7...v2.1.0
[2.0.7]: https://github.com/passbolt/passbolt_api/compare/v2.0.5...v2.0.7
[2.0.5]: https://github.com/passbolt/passbolt_api/compare/v2.0.4...v2.0.5
[2.0.4]: https://github.com/passbolt/passbolt_api/compare/v2.0.3...v2.0.4
[2.0.3]: https://github.com/passbolt/passbolt_api/compare/v2.0.2...v2.0.3
[2.0.2]: https://github.com/passbolt/passbolt_api/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/passbolt/passbolt_api/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/passbolt/passbolt_api/compare/v2.0.0-rc2...v2.0.0
[2.0.0-rc2]: https://github.com/passbolt/passbolt_api/compare/v2.0.0-rc1...v2.0.0-rc2
[2.0.0-rc1]: https://github.com/passbolt/passbolt_api/compare/v1.6.9...v2.0.0-rc1
[1.6.9]: https://github.com/passbolt/passbolt_api/compare/v1.6.5...v1.6.9
[1.6.5]: https://github.com/passbolt/passbolt_api/compare/v1.6.4...v1.6.5
[1.6.4]: https://github.com/passbolt/passbolt_api/compare/v1.6.3...v1.6.4
[1.6.3]: https://github.com/passbolt/passbolt_api/compare/v1.6.2...v1.6.3
[1.6.2]: https://github.com/passbolt/passbolt_api/compare/v1.6.1...v1.6.2
[1.6.1]: https://github.com/passbolt/passbolt_api/compare/v1.6.0...v1.6.1
[1.6.0]: https://github.com/passbolt/passbolt_api/compare/v1.5.1...v1.6.0
[1.5.1]: https://github.com/passbolt/passbolt_api/compare/v1.5.0...v1.5.1
[1.5.0]: https://github.com/passbolt/passbolt_api/compare/v1.4.0...v1.5.0
[1.4.0]: https://github.com/passbolt/passbolt_api/compare/v1.3.2...v1.4.0
[1.3.2]: https://github.com/passbolt/passbolt_api/compare/v1.3.1...v1.3.2
[1.3.1]: https://github.com/passbolt/passbolt_api/compare/v1.3.0...v1.3.1
[1.3.0]: https://github.com/passbolt/passbolt_api/compare/v1.2.1...v1.3.0
[1.2.1]: https://github.com/passbolt/passbolt_api/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/passbolt/passbolt_api/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/passbolt/passbolt_api/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/passbolt/passbolt_api/compare/v1.0.14...v1.1.0
[1.0.14]: https://github.com/passbolt/passbolt_api/compare/v1.0.13...v1.0.14
[1.0.13]: https://github.com/passbolt/passbolt_api/compare/v1.0.12...v1.0.13
[1.0.12]: https://github.com/passbolt/passbolt_api/compare/v1.0.11...v1.0.12
[1.0.11]: https://github.com/passbolt/passbolt_api/compare/v1.0.10...v1.0.11
[1.0.10]: https://github.com/passbolt/passbolt_api/compare/v1.0.9...v1.0.10
[1.0.9]: https://github.com/passbolt/passbolt_api/compare/v1.0.8...v1.0.9
[1.0.8]: https://github.com/passbolt/passbolt_api/compare/v1.0.7...v1.0.8
[1.0.7]: https://github.com/passbolt/passbolt_api/compare/v1.0.6...v1.0.7
[1.0.6]: https://github.com/passbolt/passbolt_api/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/passbolt/passbolt_api/compare/6a92766...v1.0.5
