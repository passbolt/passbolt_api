Release song: TBD

TBD

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
