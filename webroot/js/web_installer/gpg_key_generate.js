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
 * @since         2.7.0
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
  const nameInput = document.getElementsByName('name')[0];
  const emailInput = document.getElementsByName('email')[0];
  const commentInput = document.getElementsByName('comment')[0];
  const privateKeyArmoredInput = document.getElementsByName('private_key_armored')[0];
  const publicKeyArmoredInput = document.getElementsByName('public_key_armored')[0];
  const fingerprintInput = document.getElementsByName('fingerprint')[0];
  let isFormValid = true;

  /**
   * Initialize.
   */
  const init = function() {
    submitButton.addEventListener("click", submitForm, false);
  };

  /**
   * Submit the form
   * @param {Event} ev
   */
  const submitForm = async function(ev) {
    ev.preventDefault();
    if (!validateForm()) {
      return;
    }

    submitButton.setAttribute('disabled', 'disabled');
    submitButton.classList.add('processing');

    try {
      const keyPair = await generateKey(nameInput.value, emailInput.value, commentInput.value);
      const privateKey = keyPair.privateKey.trim();
      const publicKey = keyPair.publicKey.trim();
      privateKeyArmoredInput.setAttribute("value", privateKey);
      publicKeyArmoredInput.setAttribute("value", publicKey);
      const fingerprint = (await openpgp.readKey({armoredKey: privateKey})).getFingerprint().toUpperCase();
      fingerprintInput.setAttribute("value", fingerprint);
      setTimeout(() => {
        form.submit(); // wait some seconds to prevent key from being "in the future"
      }, 3000);

    } catch(error) {
      console.error(error);
    }
  };

  /**
   * Validate the form.
   * @return {boolean}
   */
  const validateForm = function() {
    isFormValid = true;

    const name = nameInput.value.trim();
    if (name == '') {
      handleFieldValidationError(nameInput, 'A server name is required.');
    } else if (!isValidUtf8(name)) {
      handleFieldValidationError(nameInput, 'The server name should contain only UTF8 characters.');
    } else {
      hideFieldValidationError(nameInput);
    }

    const email = emailInput.value.trim();
    if (email == '') {
      handleFieldValidationError(emailInput, 'A server email is required.');
    } else if (!isValidEmail(email)) {
      handleFieldValidationError(emailInput, 'The server email is not valid.');
    } else {
      hideFieldValidationError(emailInput);
    }

    const comment = commentInput.value.trim();
    if (!isValidUtf8(comment)) {
      handleFieldValidationError(commentInput, 'The comment should contain only UTF8 characters.');
    } else {
      hideFieldValidationError(commentInput);
    }

    return isFormValid;
  };

  /**
   * Check that a string is a valid UTF8 string.
   * @param {string} value The string to check
   */
  const isValidUtf8 = function(value) {
    return /^((?![\u{10000}-\u{10FFFF}]).)*$/u.test(value);
  };

  /**
   * Check that a string is a valid email string.
   * @param {string} value The string to check
   */
  const isValidEmail = function(value) {
    return /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(value);
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

  /**
   * Generate an OpenPGP key.
   * @param {string} name The user id name
   * @param {string} email The user id email
   * @param {string} comment The user id comment
   * @return {Promise<object>} Object containing the generated key pair
   * @throw Error If the key cannot be generated
   */
  const generateKey = async function(name, email, comment) {
    const keyComment = comment ? ` (${comment})` : '';
    const keyName = `${name}${keyComment}`;

    return delegateGenerateKey(keyName, email);
  };

  /**
   * Delegate the key generation to a service worker.
   * @param {string} name The key name
   * @param {string} email The key email
   * @return {Promise<object>} Object containing the generated key pair
   * @throw Error If the key cannot be generated
   */
  const delegateGenerateKey = async (name, email) => {
    const worker = new Worker('/js/web_installer/generate_key_worker.js');

    return new Promise((resolve, reject) => {
      const channel = new MessageChannel();
      channel.port1.onmessage = (result) => {
        if (result.data instanceof Error) {
          reject(result.data);
        } else {
          resolve(result.data);
        }
        worker.terminate();
      };
      worker.postMessage({ name, email }, [channel.port2]);
    });
  }

  init();
});
