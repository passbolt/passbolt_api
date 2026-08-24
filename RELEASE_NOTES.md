Release song: https://youtu.be/SLNqxQXMV1w?si=O4XNvcGI-_vByG2x

## [5.15.0] - 2026-08-20
### Added
- PB-53238 Adds a healthcheck to warn users about PHP 8.2 end of life
- PB-53302 Adds a V5150CreateSessionsTable migration to support database session storage
- PB-53303 Adds a PurgeSessions command to prune expired database sessions
- PB-53547 Adds a V5150AddSessionsModifiedIndex migration to speed up session cleanup
- PB-53548 Adds a healthcheck entry reporting which session provider the application is using
- PB-53639 Adds a healthcheck warning admins the SSO egress guard is off and will default on in the next version
- PB-53885 Adds an allowDeleteAdministrators configuration to guard SCIM administrator deletion

### Fixed
- PB-44325 Hides the "last logged in" timestamp for non-admins from the API
- PB-49590 Extends the admin-deletion email notification to also fire when a non-admin user is deleted
- PB-52221 HTML-escapes SMTP trace data in the webinstaller before rendering
- PB-52453 Closes personal-folder sharing bypass via blank metadata_key_type (Aikido#31150196)
- PB-52454 Blocks non-admin promotion of a V5 personal tag to shared via PUT /tags/{id} (Aikido#31150196)
- PB-52456 Blocks non-owner from unlinking shared V5 tags via POST /resources/{id}/tags (Aikido#31150196)
- PB-53146 Removes stack traces from missing-route exception responses
- PB-53180 Preserves historical permission levels in the activity log by no longer overwriting permissions_history on update
- PB-53260 Preserves resource tags and favorite information when one of a user's accesses is revoked but not all
- PB-53561 Rejects JWT tokens for disabled users
- PB-53563 Excludes sessions from the SQL dump command
- PB-53567 Rejects invisible characters in role names
- PB-53725 Fixes SessionPreventExtensionMiddleware creating a phantom session row for unauthenticated requests when SESSION_DEFAULTS=database
- PB-53815 Rejects invisible characters in group names, profile fields, resource names, folder names, and tag slugs

### Security
- PB-53111 Makes single-use authentication-token consumption atomic to prevent concurrent replay
- PB-53210 Fixes security vulnerability advisories affecting the guzzlehttp/guzzle package (AIKIDO-2026-231561, GHSA-wm3w-8rrp-j577, AIKIDO-2026-793560, GHSA-94pj-82f3-465w)
- PB-53215 Fixes SSO Provider URL allowing private-network SSRF (MFK-01)
- PB-53223 Fixes stored XSS in the Account-Recovery Policy Update email (MFK-06)
- PB-53357 Upgrades js-yaml (GCVE-0-2026-59869)
- PB-53780 Upgrades squizlabs/php_codesniffer to 3.13.6 (CVE-2026-67434)
- PB-53952 Upgrades js-yaml (GHSA-5p4m-2wfm-xmqj)

### Maintenance
- PB-51984 Removes remaining differences in the Webinstaller between CE and PRO repositories (MEP WP 7.2)
- PB-53119 Migrates SettingsIndexController logic into a dedicated Service layer
- PB-53270 Updates release version in file headers of the new user finders/tests (HLL)
- PB-53407 Upgrades CakePHP to v5.4.1
- PB-53453 Upgrades guzzlehttp/guzzle to 7.15.2
- PB-53655 Removes the config/schema/sessions.sql file
- PB-53874 Wires plugin table associations via Model.initialize instead of plugin bootstrap()
- Renovate: Update dependency phpstan/phpstan to v1.12.34
- Renovate: Update dependency duosecurity/duo_universal_php to v1.2.0
- Renovate: Update dependency cakephp/authentication to v3.3.7
- Renovate: Update dependency league/flysystem to v3.35.2
- Renovate: Update dependency league/oauth2-client to v2.9.0
- Renovate: Update dependency ramsey/uuid to v4.9.3
- Renovate: Update dependency spomky-labs/otphp to v11.5.0
- Renovate: Update dependency firebase/php-jwt to v7.1.0
- Renovate: Update adminer:standalone Docker digest
