Release song: https://www.youtube.com/watch?v=W8PTWqE2SVw

Passbolt is pleased to announce the immediate availability of version v4.9.1.

Passbolt v4.9.1 is a maintenance update that fixes issues reported by the community.
Among other fixes, this version addresses a compatibility issue with the PostgreSQL database, where users encountered
difficulties sharing passwords with users or groups when different cases were involved in their names.

Additionally, system administrator tools have been improved to better handle the purge of action logs on large datasets.

We would like to express our appreciation to the community for their assistance in improving Passbolt!

## [4.9.1] - 2024-08-13
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
