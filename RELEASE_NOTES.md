Release song: https//www.youtube.com/watch?v=-GxmblM_jss

## [5.3.2-test.1] - 2025-07-15
### Fixed
- PB-43910 As an administrator installing passbolt on postgres, the default postgres schema should be public
- PB-43956 Fix OpenPGP_PHP behavior discrepancy for keys with multiple self-signed key signatures with different expiry times
- PB-43746 A metadata key should be shareable with new users even if the administrator who created the key is soft-deleted
- PB-37106 As an administrator running healthCheck, I should see the right path to the logs if the directory permissions are not correct

### Maintenance
- PB-43966 Selenium specific endpoints should be enabled for local testing with ddev
- PB-43480 Writes stack traces in logs on metadata key validation 500 errors
