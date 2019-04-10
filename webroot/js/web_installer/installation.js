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
$(function () {
    const bases = document.getElementsByTagName('base');
    const baseUrl = bases[0] ? bases[0].href : '/';
    const details = [
        'Installing database',
        'Validating GPG keys',
        'Setting up keys',
        'Collecting fairy dust',
        'Setting up SMTP',
        'Locating Elon Musk\'s car. Don\'t panic.',
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
        $('.install-details').text(details[i % details.length]);
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

        $('li.selected').removeClass('selected');
        $('li.disabled').removeClass('disabled').addClass('selected');
        $('#js_step_title').text('You\'ve made it!');
        $('#js-install-installing').hide();
        $('#js-install-complete').show();
        $('#js-install-complete-redirect').attr('href', redirectUrl);

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
        $('#js_step_title').text('Oops something went wrong!');
        $('#js-install-installing').hide();
        $('#js-install-error').show();
        $('#js-install-error-message').text(response.header.message);
        $('#js-install-error-message').text(response.header.message);
        $('#js-install-error-details').text(JSON.stringify(response.body, null, 2));
        $('#js-show-debug-info').on('click', () => $('#js-install-error-details').toggle())
    }

    install();
});
