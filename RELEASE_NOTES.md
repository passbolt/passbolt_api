Release song: https://youtu.be/L3Wo8jcNrkQ

Passbolt 5.5.0-rc.1 is a feature release candidate introducing encrypted metadata in zero-knowledge mode and SCIM provisioning (beta) for automated user management.

## Encrypted Metadata Zero-Knowledge Mode

This mode is designed for organizations that prioritize privacy over server-side auditability. In this setup, the server never has access to the shared metadata private key.

* __Key distribution__: When a new user joins, the server does not distribute the metadata key.
Administrators are notified by email and can review which users are missing the key in the __Users & Groups workspace__. Keys must then be shared manually.
* __User experience__: Until the key is received, the user’s actions are limited. Operations that depend on metadata, such as sharing a resource, moving a private item into a shared folder or creating resources intended to be shared are blocked.
* __Guidance in UI__: If a restricted action is attempted, the interface provides an explanation and steps to resolve the issue.

More details are available in the dedicated [blog post](https://www.passbolt.com/blog/the-road-to-passbolt-v5-encrypted-metadata-and-other-core-security-changes-2) on encrypted metadata and zero-knowledge.

## SCIM Provisioning (Beta)

Passbolt Pro now supports __SCIM 2.0__ (beta) for automated provisioning, starting with Microsoft Entra ID (Azure AD).

* __Supported actions__: Admins can create, update, and deactivate users directly from their identity provider without using the Passbolt UI.
* __Scope__: This first release focuses on user accounts. Group synchronization will be added later.
* __Compatibility__: Okta is expected to work with this release, though some workflows may still require adjustments.

Several bugs reported by the community have also been fixed. As always, thank you to everyone who took the time to file issues and suggest improvements. Checkout the changelog for more information.

## [5.5.0-rc.1] - 2025-09-10
### Added
- PB-44617 As an administrator I can provision users using the SCIM protocol (beta)
- PB-43455 As an administrator I can edit the SCIM settings
- PB-43957 As an administrator I can view scim_entry association on users endpoints
- PB-44610 As a user authentifying with the SCIM protocol, I should not be allowed to be authentified via session
- PB-44924 As an administrator I can disable SCIM settings endpoints with an environment variable
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
