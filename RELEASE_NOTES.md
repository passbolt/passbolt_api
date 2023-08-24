Release song: https://youtu.be/fregObNcHC8

Version 4.2 of the Community Edition introduces a number of enhancements and fixes to the passbolt experience.

One of the highlights of this release is the first brick of grid  modernization. With it, you’re in control of what’s shown on the password grid. You can decide which columns you want to see, as well as their position and size. This first version is part of a larger improvement project. The aim is to make customization of the grid available and persistent with the next v4.3.0 release, and to later introduce new columns such as OTP, Icon & Tag.

Additionally, users will be pleased to see the new resource count chips displayed in the breadcrumb, providing an intuitive way to keep track of filtered resources.

Administrators are not left behind with this release as a few bugs with the command line healthcheck have been fixed and the feature is being prepared to be available in the UI soon.

Thank you for being a part of the community and for choosing passbolt.

## [4.2.0-rc.2] - 2023-08-23
### Added
- PB-24987 As an administrator I can define the password policies from the administration UI
- PB-25462 As an administrator I can deactivate RBACs with a feature flag
- PB-25036 As an administrator I can select PostgreSQL as database driver on installation
- PB-21403 As an administrator I can purge the email queue table from the command line

### Improved
- PB-24990 Performance optimisation of the cleanup command responsible to delete secrets without permissions
- PB-25263 Performance optimisation of the entry point retrieving the folders activity logs
- PB-25264 Performance optimisation of all the SQL queries retrieving user profiles
- PB-25199 Lower case UUIDs given as requests parameters before marshalling and persisting data
- PB-25389 As an administrator healthcheck/status.json requests should not be logged in the action_logs table
- PB-25734 As a user I do not want the first letters of my first and last names upper-cased when my profile is saved

### Security
- PB-25181 CSRF cookie should have secure flag set when site is served under HTTPs
- PB-25798 Fixes laminas/laminas-diactoros vulnerability by using the longwave/laminas-diactoros package

### Fixed
- PB-25472 As a user I can use an SMTP server using NTLM authentication
- PB-25475 As an administrator running the healthcheck, I should be warned for self-signed and wildcard certs instead of having a failure
- PB-25720 As an administrator I should not see a false error in the healthcheck when reading the App.base config

### Maintenance
- PB-21412 Upgrade phpstan to v1.10.15
- PB-21413 Upgrade psalm version to v5.12.0
- PB-21414 Upgrade cakephp codesniffer to v4.7
- PB-21672 Bump lorenzo/cakephp-email-queue package to 5.1
- PB-21917 Bump bcrowe/cakephp-api-pagination to v3.0.0
- PB-21918 Bump spomky-labs/otphp to v10.0.3
- PB-21919 Update enygma/yubikey package
- PB-22052 Passbolt test data version bump to v4.1.0
- PB-25379 Update vierge-noire/cakephp-fixture-factories package
- PB-24575 As a developer release notes should be automatically published on Github on new tag release
- PB-25471 As a developer Crowdin should export only a selected subset of languages
- PB-25801 As a developer I can create unpublished test packages
