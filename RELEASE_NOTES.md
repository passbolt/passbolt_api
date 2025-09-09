Release song: TBD

TBD

## [5.5.0-test.2] - 2025-09-08
### Added
- PB-44639 As an administrator, when updating metadata settings from friendly mode to zero knowledge, I should see the server key dropped in DB
- PB-44756 Updates metadata keys settings endpoint to accept server metadata private key
- PB-44752 Adds a new data check for existing resources v5 encrypted with hard or soft deleted shared metadata key

### Fixed
- PB-45060 Fixes custom fields json schema properties type
- PB-45062 Fixes user_setup_complete.php template in LU folder instead of AD
- PB-44760 Fixes health check "record not found in table organization_settings" issue (GITHUB #563)

### Maintenance
- PB-44915 Changes DDEV containers names and URLs from passbolt-ce-api to passbolt-api
- PB-44813 Updates ddev config
- PB-44772 Speeds up continuous integration by splitting pipelines in two distinct test suites
