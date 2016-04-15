# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

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
- LU: Logged in user

[Unreleased]: https://github.com/passbolt/passbolt/compare/v1.0.8...HEAD
[1.0.8]: https://github.com/passbolt/passbolt/compare/v1.0.7...v1.0.8
[1.0.7]: https://github.com/passbolt/passbolt/compare/v1.0.6...v1.0.7
[1.0.6]: https://github.com/passbolt/passbolt/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/passbolt/passbolt/compare/6a92766...v1.0.5