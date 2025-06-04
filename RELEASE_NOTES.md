Release song: https://www.youtube.com/watch?v=ZA2JknKrCbM

Passbolt v5.2.0 is now available as a release candidate.
This version introduces the long-awaited metadata properties for v5 resources (beta): users can set a custom icon for
a resource and associate multiple URIs with it. The release also ships with various bug fixes.
For full details, please see the changelog.

As always, we warmly invite the community to test these new features before the production release, and we thank
everyone for their valuable feedback and bug reports.

## [5.2.0-rc.1] - 2025-06-04
### Added
- PB-42861 As a user I can use passbolt in Slovenian language
- PB-42986 As a user I can use passbolt in Ukrainian language
- PB-42878 Add User GPG key policies (ECC by default) support behind a feature flag
- PB-41966 As a resource owner I should receive a notification on the day that my resources expire

### Improved
- PB-42706 Alias POST /metadata/keys/privates.json endpoint to POST /metadata/keys/private.json

### Fixed
- PB-42800 The check metadata key presence in the healthcheck should not fail if no metadata key is required
- PB-42701 Fixes the contain of missing metadata key on view user endpoint

### Security
- PB-42687 Security alert emails should display user IP and user agent only if configured
- PB-42378 PBL-13-001 - Fixes open redirect on MFA step in login
- PB-42379 PBL-13-004 - Fixes HTML injections in email notifications
- PB-43115 Fix XSS in email footer where the full base URL was not escaped or sanitized

### Maintenance
- PB-42935 Upgrade API babel dev dependency
- PB-42893 Upgrade API lock-link-api dev dependency
- PB-42923 refactor code to remove warning in selenium execution context
