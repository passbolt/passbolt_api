Release song: TBD

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
