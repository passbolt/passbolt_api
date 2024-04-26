Release song: https://youtu.be/3L4YrGaR8E4

Hey community members,

Prepare for an exciting update! 🥁

Passbolt is thrilled to announce that the v4.7.0 Release Candidate is officially available for testing.

The best part? All you have to do is head to GitHub and dive in! Of course, you have to make sure to follow the steps [here](https://community.passbolt.com/t/passbolt-beta-testing-how-to/7894). As always, your feedback is invaluable, please share and report any issues you come across.

Enjoy the testing journey! ♥️

## [4.7.0-rc.1] - 2024-04-26
### Added
- PB-30330 Add HTTP HEAD method support to /healthcheck/status.json to support more uptime monitoring tools (GITHUB #507)
- PB-26156 As an administrator I can configure SMTP to use TLS with a self-signed cert on my mail server (GITHUB #498)

### Security
- PB-30255 As an authenticated user I cannot access to the healthcheck endpoint when debug is on

### Fixed
- PB-30379 As an authenticating user I should not get a 500 if the gpg_auth is not an array
- PB-32889 As an administrator I should not get an exception when running core healthcheck and the host cannot be resolved
- PB-32928 As user I should see the accurate URL in the email footer when passbolt runs on multiple instances
- PB-32566 As a user setting up my account I should not get an unexpected 500
- PB-32903 Fix deprecation error on password expiry settings validation

### Maintenance
- PB-29983 Refactor health check code domain for better maintenance
- PB-30394 Moves code in ActionLogsModelListener into a dedicated service
- PB-32881 Disable by default all plugins in integration tests
- PB-32978 Use dependency proxy to reduce docker pull limit
- PB-22605 Refactor ShareSearchControllerTest, SecretViewControllerTest and GroupsDeleteControllerTest with fixture factories
- PB-32594 Add tests for SecretCreateService
