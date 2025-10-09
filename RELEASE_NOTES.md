Release song: https://www.youtube.com/watch?v=bu50DtPF1Ac

Passbolt 5.6.0 introduces standalone notes, shared metadata key rotation, and resizable sidebars. As usual, this version also brings important security hardening through dependency updates as well as a series of bug fixes and maintenance improvements.

## Standalone notes
It is now possible to create notes as a standalone resource type, without attaching them to credentials or other elements. Import and export processes have been updated to recognize and support this new type. Any imported resources that contain only a description will now be created as standalone notes.

## Shared metadata key rotation
Administrators can now rotate the shared metadata key at any time from the organization settings. This improvement marks one of the final steps in meeting metadata encryption requirements. The rotation process can be performed while the instance remains operational, so availability is not disrupted.

## Resizable sidebars
Both main workspace and Users & Groups workspace now feature sidebars that can be resized. This allows users to improve readability when working with long folder names or deeply nested folder structures. After resizing, a double-click on the sidebar handle resets it to its default width.

## Miscellaneous Improvements
The export of account kits is now compatible with larger private keys. The group membership update process has been optimized to reduce request payload size and to avoid certain size limitations. Sorting of folder names has also been improved with natural number ordering, meaning for example that "folder2" now correctly appears before "folder10."

Many thanks to everyone who shared feedback, reported issues, and helped refine these features.

## [5.6.0] - 2025-10-08
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
