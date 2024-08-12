Release song: TBD

Passbolt v4.9.1-test.1

## [4.9.1-test.1] - 2024-08-12
### Fixed
- PB-34220 As a user I can search by users and groups case insensitively on PostgreSQL

### Improved
- PB-34246 As an administrator purging the action logs table, I can set a limit option (100k per default)
- PB-34247 Adds a set of actions to be purged by the passbolt action_logs_purge command
- PB-33939 As an administrator when running bin/cake passbolt -h, I should see all the passbolt commands listed

### Maintenance
- PB-32991 Optimizes CI pipeline run time on api repositories
- PB-34219 Adds validation to retention days option in the action_logs_purge command
- PB-33333 Refactor various tests to use fixture factories
