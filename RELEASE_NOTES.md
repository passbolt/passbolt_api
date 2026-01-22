Release song: https://www.youtube.com/watch?v=QNa5o85Q-FE

Passbolt 5.9 is designed to keep upgrades predictable and everyday use reliable. It expands runtime compatibility with PHP 8.5,
makes environment risks easier to spot earlier through health checks, and closes a couple of security gaps that could otherwise be
used to probe accounts or mislead users during navigation.

**Warning:** If you run MariaDB 10.3 or 10.5, or MySQL 5, pay particular attention to the environment section below.
Support for these versions is planned to stop in January 2027, and this release starts flagging them proactively so you can schedule upgrades before they become urgent.

## Environment support and deprecation signals you can act on early
Passbolt 5.9 adds PHP 8.5 support, helping administrators and platform teams validate upcoming runtime upgrades in advance.
Moreover, while PHP 8.2 is still supported until 2027, it has entered security maintenance, and administrators should plan its upgrade this year.

At the same time, this release improves environment health checks to surface database versions that have reached end of life. MariaDB 10.3 and 10.5, and MySQL 5,
are now flagged as deprecated allowing administrators to identify risky deployments during routine maintenance rather than responding under time pressure.
These notices are explicitly tied to a planned end of support in January 2027, giving teams  a clear runway to align database upgrades with regular change windows and internal upgrade policies.

## Safer account recovery responses to reduce email enumeration risk
Account recovery endpoints can reveal whether a user exists, which makes targeted attacks easier. In Passbolt 5.9, the recover endpoint no longer leaks information when a user does
not exist in the database, reducing the signal attackers rely on for email or username enumeration.

## Stronger protection against clickjacking and deceptive overlays
Clickjacking and overlay techniques aim to trick users into clicking something different from what they believe they are interacting with. Passbolt 5.9 reinforces defenses against
these UI-level attacks in edge-case conditions, including scenarios where a compromised website tries to influence user interactions when a password could be suggested.

In practice, this extra layer of strengthening helps ensure users cannot be guided into interacting with sensitive Passbolt components when those components are not fully visible and clearly presented to them.

## Better visibility and efficiency around email digest operations
Large folder operations can generate a lot of email activity and can be difficult  to reason about as  queues grow. Passbolt 5.9 improves digest handling related to folder operations,
helping reduce unnecessary mail churn in workspaces where folder structure and permissions evolve frequently.

In addition, the passbolt _email_digest_ command now reports how many emails were sent and how many remain in the queue. This makes it easier for administrators to confirm progress,
anticipate bursts, and troubleshoot queue behavior using logs.

## Maintenance work that improves stability over time
Passbolt 5.9 continues the migration work of its UI framework for authentication-related applications. The first applications have been migrated as part of a larger foundation effort
aimed at improving stability and long-term performance as more areas move to the new framework.

## Conclusion
This release also includes additional fixes and improvements beyond the highlights above. Check out the release notes to learn more. Thanks to the community members and teams who
reported issues and helped validate fixes.

## [5.9.0] - 2026-01-22
### Added
- PB-44749 As an administrator I should get notified in the healthcheck about the deprecation of the database type and version
- PB-47893 As an administrator running the bin/cron command, I can see in the logs the number of emails left to send
- PB-46111 As a user I should receive a single email digest when more than one folders are created, updated or deleted

### Fixed
- PB-47991 As an administrator I should not get a data-check error for deleted resources with no active metadata keys
- PB-47987 As an administrator I should not get a data-check error for deleted secrets
- PB-47986 As a logged-in user tagging a resource I should not update the modified date of the resource
- PB-47070 As an administrator I can use the --no-verify option when truncating the account recovery tables

### Security
- PB-47276 As a non-logged in user I cannot enumerate user emails using the recover endpoint

### Maintenance
- PB-47701 Specify 1.1.0 version as minimum duo universal SDK package version in composer.json
- PB-47794 Update composer/composer to fix security-check job due to CVE-2025-67746
