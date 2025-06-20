Release song: https://www.youtube.com/watch?v=ZA2JknKrCbM

Passbolt 5.2 introduces the first features built on encrypted metadata, enhancing resource management and customisation.
This update lays the groundwork for future improvements and delivers practical everyday benefits.

Resources using encrypted metadata now support multiple URIs. For example, addresses like app.example.com and admin.example.com
can be linked to the same credential, helping the browser extension recognise credentials across multiple domains.

Icons and colours can now be set for resources with encrypted metadata, using a method compatible with KeePass for easy
import and export. This visual distinction helps users quickly navigate large workspaces.

A new density setting is available to adjust grid spacing, providing a clearer, more comfortable view.
Users can easily toggle this in the workspace column settings as needed.

The Passbolt interface now supports Ukrainian and Slovenian languages, enabling native speakers to use the tool comfortably without relying on English.

Additionally, resource owners now receive notifications on the day their passwords expire, supporting teams in managing rotation policies effectively.

This update includes several bug fixes and maintenance improvements based on community feedback.
Thanks to everyone who contributed by reporting issues and suggesting improvements.

## [5.2.0] - 2025-06-11
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
- PB-42379 PBL-13-004 - Fixes HTML injections in email notifications

### Maintenance
- PB-42935 Upgrade API babel dev dependency
- PB-42893 Upgrade API lock-link-api dev dependency
- PB-42923 refactor code to remove warning in selenium execution context
