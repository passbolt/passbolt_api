Release song: https://www.youtube.com/watch?v=F5uXomY94w8

Passbolt 5.8.0 introduces dynamic role management, allowing organizations to define additional roles that better align with internal policies, compliance requirements, and operational needs. This release also adds drag & drop user assignment to groups, simplifying day-to-day user and group management.

**Warning**: Ensure that all users have updated their browser extension to at least version 5.8 before assigning new roles. Otherwise, they will not be able to connect to Passbolt.

## Dynamic role management

As was already the case with the default User role, Passbolt allows administrators to restrict what users can do by limiting access to specific capabilities. With version 5.8, this model is extended beyond the default Admin and User roles, making it possible to create additional roles and assign them to users for more granular control.

Dynamic roles also enable the delegation of administrative responsibilities. Rather than granting full administrative access, administrators can now assign selected capabilities to custom roles and distribute operational tasks across multiple users. Initial support covers group creation, as well as handling account recovery requests in Passbolt Pro.

At this stage, dynamic role management comes with a defined scope and set of constraints.

- The default Admin and User roles keep fixed names and cannot be renamed or deleted.
- As before, the User role can be restricted, but it cannot be assigned delegated administrative responsibilities.
- The Admin role, by contrast, always retains access to all capabilities and cannot be restricted.
- Custom roles are currently limited to two per instance and support a first set of administrative capabilities.

This scope will be expanded progressively as additional needs and use cases are identified by the community.

## Drag & drop users to groups

Managing group membership often requires repetitive actions when working with large teams or frequently changing group structures. With Passbolt 5.8, administrators can now add users to a group by dragging them directly onto it from the Users & Groups workspace. This removes the need to open and edit each group individually and makes day-to-day group management faster and more fluid.

## Miscellaneous improvements

As usual, this release includes fixes and smaller improvements intended to improve the overall experience. For the full list of changes, please refer to the changelog.

Many thanks to everyone who provided feedback and helped refine these features.

## [5.8.0] - 2025-12-22
### Added
- PB-46972 As an administrator I can create a new custom role
- PB-46973 As an administrator I can update a custom role
- PB-46968 As an administrator I can soft delete custom roles
- PB-46971 As an administrator I can list roles including deleted ones via filter
- PB-47169 As a user I receive an email notification when my role is changed
- PB-47345 As an administrator I receive an email notification when a role is created or updated
- PB-46975 As an administrator I can list RBACs including Actions
- PB-46976 As an administrator I can update RBACs for Actions
- PB-47006 As a logged-in user my role is fetched on every request to reflect role changes immediately
- PB-47083 As a user with appropriate RBAC permissions I can create groups
- PB-47171 As a user with appropriate RBAC permissions I can manage account recovery requests
- PB-47338 As a user with account recovery view permissions I can see pending requests in users.json
- PB-47196 As an administrator I can run the healthcheck command in POSIX mode
- PB-47274 As an administrator I can run a command to populate created_by and modified_by fields in secrets
- PB-47275 As an administrator I can run a command to populate secret revisions for existing secrets

### Fixed
- PB-46374 As first admin I should not receive emails regarding encrypted metadata enablement during the first setup
- PB-46613 Fix web installer not working in HTTP when not in secure context
- PB-46640 Fix warnings in mfa_user_settings_reset_self.php email template
- PB-46645 Optimize action logs purge command dry run query
- PB-46913 Fix MfaUserSettingsDisableCommand to support case sensitive username comparison
- PB-46935 Fix 500 error on /metadata/session-keys/{uuid}.json endpoint when the request is sent twice
- PB-47236 Reduce the PHP memory load of the V570PopulateSecretRevisionsForExistingSecrets migration

### Security
- PB-46890 Upgrade js-yaml dependency (Medium severity)

### Maintenance
- PB-45979 Add CACHE_CAKETRANSLATIONS_CLASSNAME environment variable for _cake_translations_ cache config
- PB-46388 Fix PHPUnit 11 deprecations
