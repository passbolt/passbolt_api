Release song: https://www.youtube.com/watch?v=6tpGC4lgpMg

This hot-fix addresses several issues introduced in recent v5.x releases.

Since v5.3, organizations running Passbolt on servers with a locale different from en-UK could encounter issues to update or later to use the application, which have now been resolved.

It also fixes a problem where organizations that had manually disabled encrypted metadata using the kill switch available to system administrators were unable to initiate imports
credentials from the web application. This was a side effect of recent work preparing for the upcoming zero-knowledge capability, which will further strengthen the encrypted
metadata feature introduced earlier.

Finally, since v5.0, resources whose secrets had been modified, irrespective of whether the secret was a password, a TOTP, or a secure note, have had their expiration dates
automatically rotated, which was not the expected behaviour. The expected behaviour is now restored: the expiration date is rotated only when the password is edited.

We thank the community for promptly reporting these issues.

## [5.4.1] - 2025-08-13
### Fixed
- PB-44220 Enforces the format to datetime string when persisting the last_logged_in field on users login
