Release song: TBD

## [5.9.0-test.1] - 2026-01-19
### Added
- PB-44749 As an administrator I should get notified in the healthcheck about the deprecation of the database type and version
- PB-47893 As an administrator running the bin/cron command, I can see in the logs the number of emails left to send
- PB-46111 As a user I should receive a single email digest when more than one folders are created, updated or deleted

### Fixed
- PB-47991 As an administrator I should not get a data-check error for deleted resources with no active metadata keys
- PB-47987 As an administrator I should not get a data-check error for deleted secrets

### Security
- PB-47276 As a non-logged in user I cannot enumerate user emails using the recover endpoint

### Maintenance
- PB-47701 Specify 1.1.0 version as minimum duo universal SDK package version in composer.json
- PB-47794 Update composer/composer to fix security-check job due to CVE-2025-67746
