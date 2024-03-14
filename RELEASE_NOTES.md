Release song: https://youtu.be/Ub0NtPOj7es?si=3IL4HKS4-g17uPal

The Passbolt Pro 4.6.0 release "Purple Haze", brings a new SSO provider and improves administrative aspects and overall system health.

A major addition in this release is the Beta implementation of SSO AD FS (Active Directory Federation Services), enabling streamlined single sign-on capabilities for improved user access management.

Furthermore, this version incorporates the Health Check feature within the Admin workspace, offering administrators a comprehensive tool for system health assessment, thereby enhancing the platform's maintainability and reliability.

Security measures have been bolstered through the update of phpseclib/phpseclib to mitigate known vulnerabilities, alongside improvements in error logging and SSO authentication configurations.

This release also focuses on refining the platform's infrastructure for enhanced performance. It lays the groundwork for future updates by optimizing data verification processes and reducing memory usage during web activities.

The update paves the way for a series of successive enhancements with the next releases.

## [4.6.0] - 2024-03-14
### Added
- PB-24485 As an administrator I can view the API healthcheck in the administration section
- PB-29396 As an administrator I can hide the share folder capability with a RBAC
- PB-25463 As an administrator I can disable the healthcheck index endpoint with a flag
- PB-29397 As an administrator I can disable the healthcheck administration panel with a flag
- PB-29050 As a user I can identify with Microsoft ADFS SSO provider
- PB-29050 As an administrator I can enable Microsoft ADFS as SSO provider

### Improved
- PB-29009 As an administrator completing my setup I should not receive a notification that I completed my setup
- PB-26152 The API should identify openpgpjs v5.10 revoked key as revoked
- PB-29437 As an administrator I can log internal errors with the complete trace in Json format
- PB-29188 As an administrator I can see log statements on token claim errors triggered by SSO authentication
- PB-29206 As an administrator I can disable ssl peer certification verification on SSO authentication
- PB-29206 As an administrator I can define a cafile for self-signed certificate ssl on SSO authentication

### Security
- PB-30155 Update phpseclib/phpseclib to fix composer security vulnerability

### Fixed
- PB-30019 As a user I should not get a 500 when editing a user with payload containing integers as fields
- PB-29964 As an administrator disabling a user I should not get a 500 if the disabled date is not valid
- PB-29970 As a group manager I should receive an accurate summary in my notifications on group permission changes
- PB-29054 As an administrator I should not get an error when running the cleanup command and the users table does not exist
- PB-28719 As an administrator sending emails the timezone displayed in the emails should be in the correct time zone
- PB-30266 As an administrator sending emails with the email digest the message ID should be defined
- PB-27709 As an administrator I should not get an error updating directory sync settings after a server key rotation

### Maintenance
- PB-28247 Update cakephp/cakephp to version 4.5
