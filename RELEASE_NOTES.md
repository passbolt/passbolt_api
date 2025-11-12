Release song: https://youtu.be/fMnh5Tn8aeM

Passbolt 5.7.0 introduces secret history, a highly demanded feature that gives users visibility and control over previous
versions of their secrets. This release also includes several usability improvements requested and bug fixes reported by the community.

### Secret history
It is now possible to access previous revisions of a secret directly from Passbolt.

Secret history helps reduce the impact of human error and offers a safer way to manage evolving secrets. For instance,
this enables users to undo an accidental update on the spot. Note that the feature is disabled by default and requires
an administrator to enable it from the administration workspace.

### User and group workspace improvements
A new “Remove from group” action has been added to the user and group workspaces. This addition eliminates the confusion
between permanently deleting a user and simply removing them from a specific group.

Moreover, administrators can now instantly filter users that require attention via the “Attention Required” filter in
the workspace. For instance: identifying users with a pending account recovery request to review, or missing metadata keys.

### Import report
The application now displays a summary dialog after an import, offering accurate and actionable information.
The report precisely categorises alerts into successes, warnings and errors, providing end users with additional logs.

### Miscellaneous improvements
As usual this release is packed with improvements and bug fixes reported by the community. Notably, the reliability of autofill
has been improved across a wider range of websites. If you find that autofill does not work on a particular website, feel free
to open a bug report including the website details to help us identify the custom selector. For more, check out the changelog below.

Many thanks to everyone who provided feedback, reported issues, and helped refine these new features.

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
