Release song: https://youtu.be/L3Wo8jcNrkQ

Passbolt 5.5.0-rc.1 is a feature release candidate introducing encrypted metadata in zero-knowledge mode and SCIM provisioning (beta) for automated user management.

## Encrypted Metadata Zero-Knowledge Mode

This mode is designed for organizations that prioritize privacy over server-side auditability. In this setup, the server never has access to the shared metadata private key.

* __Key distribution__: When a new user joins, the server does not distribute the metadata key.
Administrators are notified by email and can review which users are missing the key in the __Users & Groups workspace__. Keys must then be shared manually.
* __User experience__: Until the key is received, the user’s actions are limited. Operations that depend on metadata, such as sharing a resource, moving a private item into a shared folder or creating resources intended to be shared are blocked.
* __Guidance in UI__: If a restricted action is attempted, the interface provides an explanation and steps to resolve the issue.

More details are available in the dedicated [blog post](https://www.passbolt.com/blog/the-road-to-passbolt-v5-encrypted-metadata-and-other-core-security-changes-2) on encrypted metadata and zero-knowledge.

Several bugs reported by the community have also been fixed. As always, thank you to everyone who took the time to file issues and suggest improvements. Checkout the changelog for more information.

## [5.5.0-rc.1] - 2025-09-12
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
