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
var ready = function ( fn ) {
  // If document is already loaded, run method
  if ( document.readyState === 'complete'  ) {
      return fn();
  }
  // Otherwise, wait until document is loaded
  document.addEventListener( 'DOMContentLoaded', fn, false );
};

ready(function () {
  const form = document.getElementsByTagName('form')[0];
  const submitButton = document.getElementById('next');
  const keyFileButton = document.getElementById('key-file-button');
  const keyFile = document.getElementById('key-file');
  const armoredKeyTextarea = document.getElementById('armored-key')
  const privateKeyArmoredInput = document.getElementsByName('private_key_armored')[0];
  const publicKeyArmoredInput = document.getElementsByName('public_key_armored')[0];
  const fingerprintInput = document.getElementsByName('fingerprint')[0];

  /**
   * Initialize.
   */
  const init = function() {
    submitButton.addEventListener("click", submitForm, false);
    keyFileButton.addEventListener("click", browseFile, false);
    keyFile.addEventListener("change", readFile, false);
  };

  /**
   * Launch the file browser.
   */
  const browseFile = function() {
    keyFile.click();
  };

  /**
   * Read the file content and update the amored key textarea.
   */
  const readFile = async function() {
    const file = keyFile.files[0];
    const reader = new FileReader();
    reader.onload = function () {
        const data = reader.result;
        armoredKeyTextarea.value = data;
        armoredKeyTextarea.innerText = data;
    };
    reader.readAsText(file);
  };

    /**
   * Submit the form
   * @param {Event} ev
   */
  const submitForm = async function(ev) {
    ev.preventDefault();
    if (!await validateForm()) {
      return;
    }

    submitButton.setAttribute('disabled', 'disabled');
    submitButton.classList.add('processing');
    const armoredKey = armoredKeyTextarea.value.trim();

    try{
      const privateKeys = await openpgp.key.readArmored(armoredKey);
      const key = privateKeys.keys[0];
      privateKeyArmoredInput.setAttribute("value", key.armor().trim());
      publicKeyArmoredInput.setAttribute("value", key.toPublic().armor().trim());
      fingerprintInput.setAttribute("value", key.primaryKey.getFingerprint().toUpperCase());
      form.submit();
    } catch(error) {
      console.error(error);
      return false;
    }
  }

  /**
   * Validate the form.
   * @return {boolean}
   */
  const validateForm = async function() {
    isFormValid = true;
    hideFieldValidationError(armoredKeyTextarea);

    const armoredKey = armoredKeyTextarea.value.trim();
    if (armoredKey == '') {
      handleFieldValidationError(armoredKeyTextarea, 'A private key is required.');
      return false;
    }

    const privateKeys = await openpgp.key.readArmored(armoredKey);
    if (privateKeys.err && privateKeys.err.length) {
      handleFieldValidationError(armoredKeyTextarea, privateKeys.err[0].message);
      return false;
    }

    const key = privateKeys.keys[0];
    if (!key.isPrivate()) {
      handleFieldValidationError(armoredKeyTextarea, 'The key is not a valid private key.');
      return false;
    }

    const expiry = await key.getExpirationTime();
    if (expiry != Infinity) {
      handleFieldValidationError(armoredKeyTextarea, 'The key cannot have an expiry date.');
      return false;
    }

    const isProtected = key.isDecrypted();
    if (!isProtected) {
      handleFieldValidationError(armoredKeyTextarea, 'The key cannot be protected with a passphrase.');
      return false;
    }

    return true;
  };

 /**
   * Handle a field error. Display an error message associated to the field.
   * @param {Element} element The target invalid field
   * @param {string} message The error message
   */
  const handleFieldValidationError = function(element, message) {
    isFormValid = false;
    const parent = element.parentElement;
    parent.classList.add('error');
    const error = parent.getElementsByClassName('message')[0];
    error.innerText = message;
    error.classList.remove('hidden');
  };

  /**
   * Hide a field error message.
   * @param {Element} element The target valid field
   */
  const hideFieldValidationError = function(element) {
    const parent = element.parentElement;
    parent.classList.remove('error');
    const error = parent.getElementsByClassName('message')[0];
    error.classList.add('hidden');
  };

  init();
});
