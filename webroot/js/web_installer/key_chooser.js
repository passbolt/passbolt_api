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
    var getFile = function () {
        return new Promise(function (resolve, reject) {
            var fileChooser = document.createElement('input');
            fileChooser.type = 'file';
            fileChooser.addEventListener('change', function () {
                var file = fileChooser.files[0];

                var reader = new FileReader();
                reader.onload = function () {
                    var data = reader.result;
                    resolve(data);
                };
                reader.readAsText(file);
                form.reset();
            });

            var form = document.createElement('form');
            form.appendChild(fileChooser);
            fileChooser.click();
        });
    };

    var onKeyContentChange = function () {
        if ($('.key-content').val() != '') {
            $('.button.next')
            .removeClass('disabled')
            .removeAttr('disabled');
        } else {
            $('.button.next')
            .addClass('disabled')
            .attr('disabled', 'disabled');
        }
    };

    $('#key-chooser').click(function () {
        getFile().then(function (fileContent) {
            $('.key-content').val(fileContent);
            onKeyContentChange();
        });
    });
    $('.key-content').on('input propertychange', function () {
        onKeyContentChange();
    });
    onKeyContentChange();

});