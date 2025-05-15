Release song: https://www.youtube.com/watch?v=d9WHUTKMD8k

The 5.1 release adds support for encrypted resource metadata features as an opt-in feature.
Early adopters can turn it on, test real-world workflows and feed back improvements,
while more cautious teams, or teams with a lot of custom integrations, can wait until they are ready.

This is a major milestone for the product, further extending Passbolt’s security model to improve
confidentiality for the contextual information surrounding credentials.
This means that metadata such as names, login URLs, and similar fields are now also cryptographically protected.
As is customary for high-risk security features, this implementation has been audited by security researchers
from Cure53 with a public report publication coming soon.

To ensure a smooth and cautious rollout, the feature is released as an opt-in capability with v5.1 and is scheduled for stable
release in v5.2. If you want to know more about how to enable it and start testing, a blog article published shortly,
will provide a step-by-step guidance on how to activate the feature and a deeper dive into what’s changed.

Additionally, the password expiry feature is now enabled by default for new installations.
This capability is considered a security best practice, helping organizations enforce rotation policies and
mitigate risks associated with long-lived shared credentials. For existing instances, administrators can enable
this feature manually from the administration workspace.
To learn more, check out the blog article: [Passbolt’s New Automation of Shared Passwords Expiry](https://www.passbolt.com/blog/passbolts-new-automation-of-shared-passwords-expiry).

As usual, this release also includes a few bug fixes and performance improvements, like a faster folder tree that handles 5,000+ folders for the ones that are running a tight ship.

As always, thank you to our community for your feedback, contributions, and bug reports. A special thanks to the CakePHP maintainers for the fast post v5 upgrade support!

For full technical details of everything in this release, check out the changelog.

## [5.1.0] - 2025-05-15
### Added
- PB-40712 Enable password expiry by default for new instances
- PB-41629 As a client I should know if the metadata plugin is set as in beta
- PB-41628 Enable the metadata plugin by default

### Fixed
- PB-41736 Adjust the datacheck command to support v5 resources
- PB-41769 Fix action_logs_purge command only purging 100 records
- PB-42108 Fix the APP_BASE variable ignored when generated URLs with CakePHP

### Security
- PB-42378 PBL-13-001 - Sanitize open redirect on MFA step in login

### Improved
- PB-41840 Return creator along metadata keys on GET /metadata/keys.json
- PB-42117 Populate metadata key ID for personal resources if null in payload

### Maintenance
- PB-40626 Update passbolt-test-data to improve PHP 8.4 compatibility
- PB-40365 Updates the test pipelines to cover PHP 8.4
- PB-40630 Bump bacon/bacon-qr-code to v3.0
- PB-40627 Bump league/flysystem package to v3.29
- PB-40625 Bump Spomky-Labs/otphp package to v11.3
- PB-40641 Replace vimeo/psalm to psalm/phar
