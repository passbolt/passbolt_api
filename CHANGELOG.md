# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.2.1]
### Fixed
- PASSBOLT-1719 GITHUB-17 The "." is not allowed in email address field
- PASSBOLT-1525 Remove unused controllers and components
- PASSBOLT-1718 Tidy up readme and contribution guidelines

## [1.2.0]
### Added
- PASSBOLT-1706 GITHUB-18 Resource Description length is too short, should be 10K characters
- PASSBOLT-1658 GITHUB-18 Resource URI length is too short, should be 1024 characters
- PASSBOLT-1637 GITHUB-14 The "+" is not allowed in the email address field while adding a new user
- PASSBOLT-1525 Test coverage for SetupControllerTest & CakeErrorController
- PASSBOLT-1694 Default config change: debug should be set to 0
- PASSBOLT-1660 Refactoring to simplify Chrome plugin development
- PASSBOLT-1649 Adjusted coveralls markup
- PASSBOLT-1648 Upgrade to Cakephp 2.9.1
- PASSBOLT-1250 Contribution guidelines

### Fixed
- PASSBOLT-1700 Event names should stay backward compatible
- PASSBOLT-1668 Remove GPGAuth debug count
- PASSBOLT-1673 Restore avatars during quick install

## [1.1.0]
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
- LU: Logged in user

[Unreleased]: https://github.com/passbolt/passbolt/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/passbolt/passbolt/compare/v1.0.14...v1.1.0
[1.0.14]: https://github.com/passbolt/passbolt/compare/v1.0.13...v1.0.14
[1.0.13]: https://github.com/passbolt/passbolt/compare/v1.0.12...v1.0.13
[1.0.12]: https://github.com/passbolt/passbolt/compare/v1.0.11...v1.0.12
[1.0.11]: https://github.com/passbolt/passbolt/compare/v1.0.10...v1.0.11
[1.0.10]: https://github.com/passbolt/passbolt/compare/v1.0.9...v1.0.10
[1.0.9]: https://github.com/passbolt/passbolt/compare/v1.0.8...v1.0.9
[1.0.8]: https://github.com/passbolt/passbolt/compare/v1.0.7...v1.0.8
[1.0.7]: https://github.com/passbolt/passbolt/compare/v1.0.6...v1.0.7
[1.0.6]: https://github.com/passbolt/passbolt/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/passbolt/passbolt/compare/6a92766...v1.0.5