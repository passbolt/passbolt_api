Release song: https://www.youtube.com/watch?v=GXbOROT0OuA

Passbolt 5.12.0-test.3

## [5.12.0-test.3] - 2026-05-07
### Added
- PB-51081 Add pin code resource type
- PB-51516 Enable Safari by default

### Security
- PB-50625 Fix GHSA-F886-M6HF-6M8V security vulnerability advisory (Medium)
- PB-50340 Upgrade picomatch package (Medium)
- PB-50538 Upgrade lodash package (Critical)
- PB-50895 Fix bn.js security vulnerability advisory GHSA-378v-28hj-76wf (Medium)
- PB-50969 Fix composer security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-40194)
- PB-51135 Fix security vulnerability advisory affecting composer/composer package (CVE-2026-40261, CVE-2026-40176)
- PB-51151 Fix i18next-http-backend security vulnerability advisory GHSA-r5fr-rjxr-66jc (Medium)
- PB-51152 Fix uuid security vulnerability advisory GHSA-w5hq-g745-h8pq (Medium)
- PB-51448 Fix security vulnerability advisory affecting phpseclib/phpseclib package (CVE-2026-44167)
- PB-51208 Cleanup UserScimResource.php logged errors

### Maintenance
- PB-50893 As an administrator I can purge action additional logs by action via the logs purge command
- PB-50914 Homogenize CE and Pro codebase
- PB-51243 Fix activity logging breaking after instance reset while executing Selenium tests
- PB-51428 Fix dev test data inserting empty definitions for v5 resource types
