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
    const keyContent = document.querySelector('.key-content');
    const nextButton = document.querySelector('.button.next');
    const keyChooser = document.getElementById('key-chooser');

    const getFile = () => {
        return new Promise((resolve) => {
            const fileChooser = document.createElement('input');
            fileChooser.type = 'file';
            fileChooser.addEventListener('change', () => {
                const file = fileChooser.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    resolve(reader.result);
                };
                reader.readAsText(file);
                form.reset();
            });

            const form = document.createElement('form');
            form.appendChild(fileChooser);
            fileChooser.click();
        });
    };

    const onKeyContentChange = () => {
        if (keyContent.value !== '') {
            nextButton.classList.remove('disabled');
            nextButton.removeAttribute('disabled');
        } else {
            nextButton.classList.add('disabled');
            nextButton.setAttribute('disabled', 'disabled');
        }
    };

    keyChooser.addEventListener('click', () => {
        getFile().then((fileContent) => {
            keyContent.value = fileContent;
            onKeyContentChange();
        });
    });
    keyContent.addEventListener('input', onKeyContentChange);
    onKeyContentChange();
});
