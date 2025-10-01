Release song: https://youtu.be/RyP8hGuyknA

Passbolt 5.6.0 is a feature release that brings standalone notes, shared metadata key rotation and resizable sidebars. This release comes as usual with security reinforcement by updating 3rd party libraries and other bug fixes.

## Standalone notes

This new resource type allows the user to create notes without any other attached elements (i.e. password). Import and export are updated to take into account this new resource type.
Thus, imported resources with only a description will be set as standalone notes.

## Shared metadata key rotation

Now the shared metadata key can be rotated. This marks one of the final steps in the metadata encryption requirements. Administrators can, at any time, rotate this key through the organisation settings while maintaining the Passbolt instance in a working state.

## Resizable sidebars

On the resource workspace and the user workspace, the 2 sidebars are now resisable. This brings more customisation for users and helps readability (i.e for long folder names or folders in deep folder tree).
After resizing a sidebar, a simple double click on the handle brings the sidebar to its default width.

## Miscellaneous

Different bug fixes and maintenance update are into the party:
- export of account kit is compatible with bigger private keys
- group membership update process is updated to reduce request size and avoid some size limitations
- folders name sort includes now natural number counting

## [5.6.0-test.1] - 2025-10-01
### Added
- PB-45058 Add datacheck to check for existing metadata key with no metadata private keys
- PB-44187 As an admin I cannot delete a metadata key associated with a deleted resource
- PB-44183 As a user that is sole owner of v4 resources when v4 resources types are disabled, v4 resources should be ignored on an ownership transfer request
- PB-44770 As a user I want to configure the trusted_proxies list as an environment variable
- PB-45471 Add new database migration to add standalone notes resource type
- PB-45472 Update resource types endpoints tests to assert enable/disable is working for new standalone notes resource type
- PB-45473 Update resources endpoints tests to accommodate new standalone notes resource type

### Fixed
- PB-45222 Fix EmailDigest not working for v5 resources
- PB-45447 Fix PUT /metadata/keys/<uuid>.json endpoint returning 500 error with trailing data
- PB-45436 As an administrator I can define the default cache engine with an environment variable
- PB-45454 Fix 500 error due to MySQL deadlock on create resource endpoint
- PB-45456 Allow editing of v4 resources even when v4 resource type creation is disabled
- PB-45258 Fix grammatical errors in the resource update email content
- PB-45057 Reduce memory consumption on the action logs endpoints
- PB-45057 Reduce memory consumption on resources and folders index endpoints

### Maintenance
- PB-44813 Bring back DDEV ldap related services for development environment
- PB-44593 Bump i18next version
- PB-45161 Fix regularly failing UsersIndexControllerPaginationTest.php test
- PB-45270 Add custom exception message with client IP in /healthcheck/error.json
- PB-45062 Fix user_setup_complete.php template in LU folder instead of AD
