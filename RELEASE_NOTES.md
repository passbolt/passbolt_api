Release song: TBD

The Passbolt API V5 (test.4).

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
