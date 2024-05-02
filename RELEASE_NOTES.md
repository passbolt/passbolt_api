Release song: https://youtu.be/3L4YrGaR8E4

Passbolt Pro v4.7 is a maintenance release that resolves multiple issues identified by the community. Furthermore, this release supports the commitment to improving customization options and integration features, making it easier for organizations to tailor the system to their specific needs.

A key enhancement in this release is the ability for administrators to use custom SSL certificates for SMTP and Users Directory server connections. These long-awaited features are particularly beneficial for organizations operating in air-gapped environments or those using their own root CAs, enabling passbolt to more securely integrate with internal tools. All of these customizations are visible in the API status report of the administration workspace, providing a clear and manageable overview for administrators.

Moreover, the integration with user directories has been improved, now enabling the synchronization of user accounts using multiple fields as email identifiers. This allows organizations with heterogeneous data environments to synchronize more seamlessly with passbolt. This improvement is part of a broader initiative aimed at modernizing the integration with your user directories. Stay tuned, more enhancements are planned for future releases.

## [4.7.0] - 2024-04-30
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
