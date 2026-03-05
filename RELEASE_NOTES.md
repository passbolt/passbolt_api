Release song: TBD

## [5.10.0-test.3] - 2026-05-05
### Fixed
- PB-43183 Improve folders cascade delete performance by refactoring code using iterative BFS and batch operations
- PB-49148 Fix PBL-15-002 WP3: Suboptimal token generation randomness
- PB-49323 As a user creating a resource, I should not get a 500 if the secret passed is not an array of secrets
- PB-49152 PBL-15-004 WP1: Fix unsalted SHA256 hashing of bearer tokens in SCIM
- PB-49158 PBL-15-010 WP4: Fix Directory entry foreign key race condition
- PB-49153 PBL-15-005 WP2: Fix race condition in SCIM user creation endpoint
- PB-40266 Health-check issues on Ubuntu 24 when running while being in a directory without the +x permission bit for www-data user (GITHUB #571)
- PB-49158 PBL-15-010 WP4: Fix Directory entry foreign key race condition
- PB-47973 Improve Active Directory entries to allow long entries

### Security
- PB-49154 PBL-15-006 WP2: User enumeration via error messages
