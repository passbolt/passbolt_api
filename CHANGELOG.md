# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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

## Added
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
- AD: Admin

[Unreleased]: https://github.com/passbolt/passbolt_api/compare/v3.5.0...HEAD
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
