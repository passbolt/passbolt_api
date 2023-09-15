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
    $('.see-trace').click(function () {
        if ($('.trace').hasClass('hidden')) {
            $('.trace').removeClass('hidden');
        } else {
            $('.trace').addClass('hidden');
        }

        return false;
    });

    $.fn.setSmtpConfigInputs = function(authMethod) {
        if (authMethod === 'username_only') {
            // Hide from UI
            $('#smtp-config-input-username').show();
            $('#smtp-config-input-password').hide();
            // Clear values from the input
            $('input[name="password"]').val('');
        } else if (authMethod === 'none') {
            // Hide from UI
            $('#smtp-config-input-username').hide();
            $('#smtp-config-input-password').hide();
            // Clear values from the inputs
            $('input[name="username"]').val('');
            $('input[name="password"]').val('');
        } else {
            // Hide from UI
            $('#smtp-config-input-username').show();
            $('#smtp-config-input-password').show();
        }
    };

    var authMethodElem = $('select[name="authentication_method"]');

    $(this).setSmtpConfigInputs(authMethodElem.val());

    authMethodElem.on('change', function() {
        $(this).setSmtpConfigInputs(this.value);
    });
});
