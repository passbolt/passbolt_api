Release song: https://www.youtube.com/watch?v=ubWL8VAPoYw

Passbolt 5.3 adds custom fields, one of the five most‑requested features from the community.
Built on top of encrypted‑metadata introduced earlier this year, custom fields let users attach
additional key‑value pairs to a resource or as a standalone one.
Typical use‑cases include centralising CI/CD job variables and storing environment‑specific
configuration values that need more structure than a general note.

As part of our continuous performance work, this release concentrates on folder browsing.
Loading folders and their resources is now faster and reduces the load on API and client,
improving day-to-day usability for organizations having thousands of credentials under management.

Several bugs reported by the community have also been fixed. As always, thank you to everyone who
took the time to file issues, test patches and suggest improvements.
For a complete list of changes, consult the changelog.

## [5.3.0] - 2025-07-09
### Added
- PB-43066 As a developer I can setup my development environment using ddev
- PB-43188 Adds Custom Fields Standalone resource type migration
- PB-43383 Improves the performance of most paginated endpoints
- PB-43659 Improve error handling when metadata key could not be saved due to unexpected reason

### Fixed
- PB-43382 As an administrator I should not get a connection error when running the healthcheck when the database is empty
- PB-43007 Fixes emails not sent after v5 upgrade if SMTP credentials are stored in environment variables (GITHUB #545)
- PB-43122 As an administrator retrieving users metadata key, I should not trigger a validation type issue on the missing metadata_keys_id in certain conditions
- PB-43137 Fixes a potential settings conflict in user key policy where key of type rsa should not have a preferred curve
- PB-42784 As an administrator I should not get a health check error when all email notifications are enabled
- PB-43259 Fixes a record not found error on table `organization_settings` in healthcheck after v5 upgrade (GITHUB #548)
- PB-42072 As a user sharing permissions, I should not get a 500 response if the payload is wrongly formatted
- PB-42071 Fixes 500 errors on malformed UTF-8 character-based URLs when using the JsonTraceFormatter class
- PB-43659 Improves the error catching on creation of organization metadata key

### Maintenance
- PB-42177 Upgrades CakePHP version to 5.2.5
- PB-43010 Replaces the use of static fixtures with fixture factories in multiple test cases
- PB-41724 Map _cake_core_ cache config with _cake_translations_ in the bootstrap.php file
- PB-42380 Adds the missing v5 resource types on data insertion in the passbolt-test-data vendor
- PB-43658 Use FQN to load all vendor plugin
