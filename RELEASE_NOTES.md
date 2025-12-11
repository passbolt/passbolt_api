Release song: TBD

## [5.8.0-test.1] - 2025-12-11
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
