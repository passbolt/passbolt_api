Release song: https://www.youtube.com/watch?v=kymdKYtkJbQ

Passbolt v5.4.0 ships with encrypted metadata and the accompanying new resource types promoted to stable. These capabilities have been battle-tested for months, and the most remaining edge cases have been smoothed out so they can now be enabled for everyone.

Removing the beta label means that every new instance starts with encrypted metadata activated by default. As a result, features introduced in previous releases, such as icons, multiple URIs and custom fields, are available from day one without any action from end-users.

For existing instances, the activation process has been simplified: administrators can decide with a single click whether their organisation is ready or would prefer to postpone the launch. Once enabled, the instance immediately supports the new resource types and their extended capabilities. Because the change may disrupt external integrations, existing content is not migrated automatically, migration remains the responsibility of content owners or administrators. It can be performed item-by-item by users in the main workspace or organisation-wide with the resource-metadata administration migration tool.

## [5.4.0-test.2] - 2025-08-07
### Fixed
- PB-44578 Align metadata setup settings entry point variable name with client
