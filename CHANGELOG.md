# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

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

## [1.3.0] - 2016-25-11
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
- LU: Logged in user

[Unreleased]: https://github.com/passbolt/passbolt_api/compare/v1.6.5...HEAD
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
