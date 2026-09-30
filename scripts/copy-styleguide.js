/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.12.0
 */
const fs = require('node:fs');
const path = require('node:path');

const STYLEGUIDE = 'node_modules/passbolt-styleguide';
const WEBROOT = 'webroot';

function copy(src, dest, filter) {
  if (!fs.existsSync(src)) {
    return;
  }

  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.cpSync(src, dest, { recursive: true, filter });
}

copy(`${STYLEGUIDE}/src/fonts`, `${WEBROOT}/fonts`);
copy(`${STYLEGUIDE}/src/img/webroot`, WEBROOT);
copy(`${STYLEGUIDE}/src/locales`, `${WEBROOT}/locales`);

const SRC_IMG = `${STYLEGUIDE}/src/img`;
const DEST_IMG = `${WEBROOT}/img`;

copy(`${SRC_IMG}/avatar`, `${DEST_IMG}/avatar`);
copy(
  `${SRC_IMG}/themes`,
  `${DEST_IMG}/themes`,
  (src) => fs.statSync(src).isDirectory() || src.endsWith('.png'),
);


const imgFiles = [
  'logo/logo.png',
  'logo/logo.svg',
  'logo/logo_white.svg',
  'controls/check_black.svg',
  'controls/check_tick.svg',
  'controls/chevron-down_black.svg',
  'controls/chevron-down_blue.svg',
  'controls/dot_white.svg',
  'controls/dot_red.svg',
  'controls/dot_black.svg',
  'controls/infinite-bar.gif',
  'controls/loading_light.svg',
  'controls/loading_dark.svg',
  'controls/overlay-opacity-50.png',
  'controls/success.svg',
  'controls/fail.svg',
  'controls/warning.svg',
  'controls/attention.svg',
  'third_party/FirefoxAMO_black.svg',
  'third_party/FirefoxAMO_white.svg',
  'third_party/ChromeWebStore_black.svg',
  'third_party/ChromeWebStore_white.svg',
  'third_party/appstore.svg',
  'third_party/edge-addon-black.svg',
  'third_party/edge-addon-white.svg',
  'third_party/firefox.svg',
  'third_party/chrome.svg',
  'third_party/edge.svg',
  'third_party/brave.svg',
  'third_party/vivaldi.svg',
  'third_party/safari.svg',
  'third_party/aws-ses.svg',
  'third_party/elastic-email.svg',
  'third_party/gmail.svg',
  'third_party/mailgun.svg',
  'third_party/mailjet.svg',
  'third_party/mandrill.svg',
  'third_party/sendgrid.svg',
  'third_party/sendinblue.svg',
  'third_party/zoho.svg',
  'third_party/outlook.svg',
  'third_party/office365.svg',
  'illustrations/email.png',
  'diagrams/totp.svg',
  'third_party/duo.svg',
  'third_party/google-authenticator.svg',
  'third_party/yubikey.svg',
];

for (const file of imgFiles) {
  copy(`${SRC_IMG}/${file}`, `${DEST_IMG}/${file}`);
}

const themes = ['default', 'midgar', 'solarized_light', 'solarized_dark'];
const cssFiles = ['api_main.min.css', 'api_authentication.min.css', 'ext_authentication.min.css'];
for (const theme of themes) {
  for (const file of cssFiles) {
    copy(`${STYLEGUIDE}/build/css/themes/${theme}/${file}`, `${WEBROOT}/css/themes/${theme}/${file}`);
  }
}

const jsApps = [
  'api-account-recovery.js',
  'api-app.js',
  'api-recover.js',
  'api-setup.js',
  'api-triage.js',
  'api-vendors.js',
  'api-feedback.js',
  'api-mfa-verify.js',
  'api-mfa-verify-delegated.js',
];

for (const file of jsApps) {
  copy(`${STYLEGUIDE}/build/js/dist/${file}`, `${WEBROOT}/js/app/${file}`);
}
