Release song: https://youtu.be/Ub0NtPOj7es?si=3IL4HKS4-g17uPal

The Passbolt Community Edition 4.6.0 release "Purple Haze", brings enhancements that focus primarily on the administrative aspect and overall system health.

This update introduces the Health Check feature within the Admin workspace, designed to offer administrators a comprehensive tool for system assessment and upkeep.
In addition, this version addresses a range of minor bugs and delivers the awaited PHP 8.3 support.

This version furthermore lays the foundations for successive performance gains by refining data verification processes and reducing memory usage, particularly when browsing. Expect more significant improvements with the next releases.

## [4.6.0] - 2024-03-14
### Added
- PB-24485 As an administrator I can view the API healthcheck in the administration section
- PB-29396 As an administrator I can hide the share folder capability with a RBAC
- PB-25463 As an administrator I can disable the healthcheck index endpoint with a flag
- PB-29397 As an administrator I can disable the healthcheck administration panel with a flag

### Improved
- PB-29009 As an administrator completing my setup I should not receive a notification that I completed my setup
- PB-26152 The API should identify openpgpjs v5.10 revoked key as revoked
- PB-29437 As an administrator I can log internal errors with the complete trace in Json format

### Security
- PB-30155 Update phpseclib/phpseclib to fix composer security vulnerability

### Fixed
- PB-30019 As a user I should not get a 500 when editing a user with payload containing integers as fields
- PB-29964 As an administrator disabling a user I should not get a 500 if the disabled date is not valid
- PB-29970 As a group manager I should receive an accurate summary in my notifications on group permission changes
- PB-29054 As an administrator I should not get an error when running the cleanup command and the users table does not exist
- PB-28719 As an administrator sending emails the timezone displayed in the emails should be in the correct time zone
- PB-30266 As an administrator sending emails with the email digest the message ID should be defined
- PB-30182 Build the styleguide on version 4.6.1

### Maintenance
- PB-28247 Update cakephp/cakephp to version 4.5
