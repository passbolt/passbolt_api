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
 * @since         2.0.0
 */
document.addEventListener('DOMContentLoaded', () => {
    const bases = document.getElementsByTagName('base');
    const baseUrl = bases[0] ? bases[0].href : '/';
    const details = [
        'Installing database',
        'Validating GPG keys',
        'Setting up keys',
        'Collecting fairy dust',
        'Setting up SMTP',
        'Passwords so secret, even we didn\'t know we had them.',
        'Checking options',
        'Writing configuration file',
        'Brewing pale ale',
        'Checking status'
    ];
    let rollStatusTimeout;

  /**
   * Display status.
   */
    function rollStatus(i)
    {
        i = i != undefined ? i : 0;
        document.querySelector('.install-details').textContent = details[i % details.length];
        rollStatusTimeout = setTimeout(() => {
            i++;
          rollStatus(i);
        }, 1000);
    }

  /**
   * Request the API to install passbolt
   */
    async function install()
    {
        rollStatus();
        const installUrl = `${baseUrl}install/installation/do_install.json`;
        const response = await fetch(installUrl, {
          credentials: "same-origin"
        });
        clearTimeout(rollStatusTimeout);
        const json = await response.json();
        if (response.ok) {
            handleInstallSuccess(json);
        } else {
            handleInstallError(json);
        }
    }

  /**
   * Handle install success response
   * @param response
   */
    function handleInstallSuccess(response)
    {
        let redirectUrl = baseUrl;
        if (response.user_id) {
            redirectUrl = `${baseUrl}setup/install/${response.user_id}/${response.token}`;
        }

        const selected = document.querySelector('li.selected');
        if (selected) selected.classList.remove('selected');

        document.querySelectorAll('li.disabled').forEach(el => {
            el.classList.remove('disabled');
            el.classList.add('selected');
        });

        document.getElementById('js_step_title').textContent = 'You\'ve made it!';
        document.getElementById('js-install-installing').classList.add('hidden');
        document.getElementById('js-install-complete').classList.remove('hidden');
        document.getElementById('js-install-complete-redirect').setAttribute('href', redirectUrl);

        setTimeout(function () {
            document.location.href = redirectUrl;
        }, 5000);
    }

  /**
   * Handle install error response
   * @param response
   */
    function handleInstallError(response)
    {
        document.getElementById('js_step_title').textContent = 'Oops something went wrong!';
        document.getElementById('js-install-installing').classList.add('hidden');
        document.getElementById('js-install-error').classList.remove('hidden');
        document.getElementById('js-install-error-message').textContent = response.header.message;
        document.getElementById('js-install-error-details').textContent = JSON.stringify(response.body, null, 2);
        document.getElementById('js-show-debug-info').addEventListener('click', () => {
            const details = document.getElementById('js-install-error-details');
            details.classList.toggle('hidden');
        });
    }

    install();
});
