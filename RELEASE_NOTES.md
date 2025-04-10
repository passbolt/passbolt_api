Release song: https://www.youtube.com/watch?v=yf1f8zNvR1I

“Even the longest day has its end” goes the old Irish proverb, and here we are at long last announcing the Passbolt 5.x series.
This first release v5.0 ships with a complete redesign of the application’s interface, which had remained largely unchanged since
Passbolt’s early days and was limiting the addition of new capabilities.
The new version offers a more spacious layout that provides room for meaningful information and addresses long-standing ergonomic issues.
If you want to know more, check out what changed in this [Passbolt 5, UI Redesign](https://community.passbolt.com/t/passbolt-5-ui-redesign/12717) community post.

Passbolt v5.0 lays the groundwork for the v5.x series, which will expand the software’s capabilities to handle more sensitive data types that the community has requested,
such as key-value pairs, SSH keys, and certificates. The blog article about the passbolt v5.0 release is coming soon.

Of course, with each major version come the inevitable breaking changes, which we strive to minimize as much as possible.
With this release, the minimum server requirement has changed to PHP 8.2 or greater, so be sure to check out our latest blog article on [how to upgrade to PHP 8.2](https://www.passbolt.com/blog/preparing-for-passbolt-v5-php-8-2-requirement).
Additionally this is a perfect moment to back up your server data and prepare for the unexpected.

Thank you to the community for all your feedback, testing, and support in making this milestone possible. We hope you’ll enjoy what Passbolt v5.0 has to offer and look forward to hearing from you.

## [5.0.0] - 2025-04-10
### Added
- PB-39434 As an administrator I can log user actions on file in an SIEM compatible format
- PB-39627 Enforce PHP 8.2 as minimum passbolt API requirement
- PB-40155 Add Passbolt API support of PHP 8.4
- PB-40247 Add API status documentation link to the health check command

### Fixed
- PB-39706 When creating a user from CLI the metadata_private_keys should have their fields created_by and modified_by set
- PB-41356 As an administrator I can delete a resource type associated to deleted resources
- PB-41374 Fix unlimited session lifetime introduced in CakePHP 5
- PB-41379 Updates the minimum next version to 8.2 to remove false warning from installation

### Maintenance
- PB-28246 Refactor the whole application to upgrade CakePHP to version 5
- PB-39434 Add code coverage to ActionLogsUsernameQueryStrategy
- PB-39660 Mock MfaFormInterface to avoid tests failing occasionally
- PB-39630 Fix ResourcesIndexControllerPaginationTest recurrently failing test
