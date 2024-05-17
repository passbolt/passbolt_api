Release song: https://youtu.be/hbe3CQamF8k

Passbolt is pleased to announce that the v4.8.0 Release Candidate is officially available for testing.
This maintenance release aims to publish the first version of the Manifest v3 browser extension for Chrome and adds tools for administrators to help them maintain their instances.
As always, your feedback is invaluable, so please share and report any issues you come across.

## [4.8.0-rc.1] - 2024-05-17
### Added
- PB-33071 As an administrator I can purge the action logs table with a dedicated command
- PB-33231 As an administrator I want to know if a custom certificate is in use for SMTP
- PB-32579 As an administrator I can view email_queue records via passbolt command

### Improved
- PB-32888 As an admin I should not get a time-out on health checks on air-gapped network
- PB-32983 Access email settings only when emails are sent

### Fixed
- PB-33451 Fix 500 error on authentication when nonce is not a string
- PB-33073 As a user logging in, invalid login operation should not be logged as success in the audit logs
- PB-33234 The application should not throw an error if the JWT public key is not parsable

### Maintenance
- PB-30314 Bump passbolt/passbolt-test-data to v4.8
