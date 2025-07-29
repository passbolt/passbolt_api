Release song: https//www.youtube.com/watch?v=-GxmblM_jss

Passbolt v5.3.2 is a security release designed to strengthen the security posture of your organization. It introduces a
clipboard flushing feature, adds safeguards around SSO settings, and addresses issues related to encrypted metadata.

The new clipboard flush timer lets you copy secrets just long enough to use them; clipboard data is automatically cleared
when the countdown (30s) expires, significantly reducing the risk of accidental exposure or leaks from forgotten clipboard content.

Additionally, SSO admin-settings edit endpoints of self-hosted instances can now be locked, reducing potential exposure
to scans if an administrator account is compromised. You can verify if this protection is active, and get instructions
on how to set it up, by running the health check via the server command line.

This update also resolves several encrypted metadata issues, moving the feature closer to general availability.
Organizations can now enable encrypted metadata even if users have imported their own more complex keys
(e.g. keys that were set to expire at some point), streamlining adoption for advanced users. Admin changes are smoother
too: if the original metadata-enabling administrator leaves, newly invited users will still receive the metadata key automatically,
removing the need for manual distribution. Lastly, users who owned shared resources using the new encrypted metadata format can now
be deleted without issue, as ownership transfer is now handled correctly during the deletion process.
A big thank you to all testers who helped refine these features. If you’re new to any of them, we welcome your feedback on the community
forum or through your usual support channels!

## [5.3.2] - 2025-07-16
### Added
- PB-43467 As an administrator, I can lock the SSO configuration to mitigate potential scanning through the endpoints

### Fixed
- PB-43910 As an administrator installing passbolt on postgres, the default postgres schema should be public
- PB-43956 Fix OpenPGP_PHP behavior discrepancy for keys with multiple self-signed key signatures with different expiry times
- PB-43746 A metadata key should be shareable with new users even if the administrator who created the key is soft-deleted
- PB-37106 As an administrator running healthCheck, I should see the right path to the logs if the directory permissions are not correct

### Maintenance
- PB-43966 Selenium specific endpoints should be enabled for local testing with ddev
- PB-43480 Writes stack traces in logs on metadata key validation 500 errors
