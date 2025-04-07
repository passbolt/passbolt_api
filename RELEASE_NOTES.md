Release song: TBD

The Passbolt API v5 is now available as a release candidate.
We have updated the CakePHP library to version 5. The PHP minimum version is now 8.2, and version 8.4 is now supported!
See the blog post [here](https://www.passbolt.com/blog/preparing-for-passbolt-v5-php-8-2-requirement) on how to upgrade to PHP 8.2. For more detailed information, please refer to the changelogs.

We would also like to thank the community for their invaluable feedback.

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
