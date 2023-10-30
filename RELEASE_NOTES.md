TBD

## [4.4.0-test.2] - 2023-10-30
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
