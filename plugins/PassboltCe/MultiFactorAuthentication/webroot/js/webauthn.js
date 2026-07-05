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
 * @since         5.14.0
 *
 * Drives the WebAuthn (FIDO2) registration and authentication ceremonies from the top-level page
 * served by the passbolt origin, so navigator.credentials.* binds to the correct relying party id.
 * Configuration is read from the #webauthn-ceremony element's data attributes (no inline script, to
 * comply with the "script-src 'self'" content security policy).
 */
(function () {
  'use strict';

  /**
   * Decode an unpadded base64url string to an ArrayBuffer.
   * @param {string} value base64url encoded value
   * @returns {ArrayBuffer}
   */
  function base64UrlToBuffer(value) {
    const padding = '='.repeat((4 - (value.length % 4)) % 4);
    const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
    const binary = window.atob(base64);
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
      bytes[i] = binary.charCodeAt(i);
    }
    return bytes.buffer;
  }

  /**
   * Encode an ArrayBuffer to an unpadded base64url string.
   * @param {ArrayBuffer} buffer
   * @returns {string}
   */
  function bufferToBase64Url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  }

  /**
   * Map a list of credential descriptors, decoding their id.
   * @param {Array|undefined} descriptors
   * @returns {Array}
   */
  function decodeDescriptors(descriptors) {
    return (descriptors || []).map(function (descriptor) {
      return Object.assign({}, descriptor, { id: base64UrlToBuffer(descriptor.id) });
    });
  }

  /**
   * Build the publicKey options for a registration ceremony from the server payload.
   * @param {Object} options credentialCreationOptions
   * @returns {Object}
   */
  function toCreationOptions(options) {
    const publicKey = Object.assign({}, options);
    publicKey.challenge = base64UrlToBuffer(options.challenge);
    publicKey.user = Object.assign({}, options.user, { id: base64UrlToBuffer(options.user.id) });
    publicKey.excludeCredentials = decodeDescriptors(options.excludeCredentials);
    return publicKey;
  }

  /**
   * Build the publicKey options for an authentication ceremony from the server payload.
   * @param {Object} options credentialRequestOptions
   * @returns {Object}
   */
  function toRequestOptions(options) {
    const publicKey = Object.assign({}, options);
    publicKey.challenge = base64UrlToBuffer(options.challenge);
    publicKey.allowCredentials = decodeDescriptors(options.allowCredentials);
    return publicKey;
  }

  /**
   * Serialize a PublicKeyCredential returned by navigator.credentials into the WebAuthn JSON shape
   * expected by the server (base64url encoded binary fields).
   * @param {PublicKeyCredential} credential
   * @returns {Object}
   */
  function serializeCredential(credential) {
    const response = credential.response;
    const serialized = {
      id: credential.id,
      rawId: bufferToBase64Url(credential.rawId),
      type: credential.type,
      clientExtensionResults: credential.getClientExtensionResults ? credential.getClientExtensionResults() : {},
      response: {}
    };

    serialized.response.clientDataJSON = bufferToBase64Url(response.clientDataJSON);

    if (typeof response.attestationObject !== 'undefined') {
      // Registration (attestation) response.
      serialized.response.attestationObject = bufferToBase64Url(response.attestationObject);
      if (typeof response.getTransports === 'function') {
        serialized.response.transports = response.getTransports();
      }
    } else {
      // Authentication (assertion) response.
      serialized.response.authenticatorData = bufferToBase64Url(response.authenticatorData);
      serialized.response.signature = bufferToBase64Url(response.signature);
      serialized.response.userHandle = response.userHandle ? bufferToBase64Url(response.userHandle) : null;
    }

    return serialized;
  }

  /**
   * Perform a JSON POST against a passbolt endpoint, returning the parsed body.
   * @param {string} url
   * @param {string} csrfToken
   * @param {Object} payload
   * @returns {Promise<Object>}
   */
  function postJson(url, csrfToken, payload) {
    return fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-Token': csrfToken || ''
      },
      body: JSON.stringify(payload)
    }).then(function (response) {
      return response.json().then(function (json) {
        const status = json && json.header && json.header.status;
        if (!response.ok || status !== 'success') {
          const message = (json && json.header && json.header.message) || 'The request failed.';
          throw new Error(message);
        }
        return json.body || {};
      });
    });
  }

  /**
   * Translate a native WebAuthn error into a human friendly message.
   * @param {Error} error
   * @returns {string}
   */
  function friendlyError(error) {
    if (error && error.name === 'NotAllowedError') {
      return 'The operation was cancelled or timed out. Please try again.';
    }
    if (error && error.name === 'InvalidStateError') {
      return 'This security key is already registered.';
    }
    if (error && error.name === 'AbortError') {
      return 'The operation was aborted. Please try again.';
    }
    return (error && error.message) ? error.message : 'Something went wrong. Please try again.';
  }

  /**
   * Wire the ceremony to the page.
   * @param {HTMLElement} root
   */
  function init(root) {
    if (!window.PublicKeyCredential || !navigator.credentials) {
      showError(root, 'This browser does not support security keys.');
      disableTrigger(root);
      return;
    }

    const trigger = root.querySelector('.js-webauthn-trigger');
    if (!trigger) {
      return;
    }
    trigger.addEventListener('click', function () {
      run(root, trigger);
    });
  }

  /**
   * Run the ceremony matching the configured mode.
   * @param {HTMLElement} root
   * @param {HTMLButtonElement} trigger
   */
  function run(root, trigger) {
    const mode = root.getAttribute('data-mode');
    const beginUrl = root.getAttribute('data-begin-url');
    const finishUrl = root.getAttribute('data-finish-url');
    const redirect = root.getAttribute('data-redirect');
    const csrfToken = root.getAttribute('data-csrf-token');

    clearError(root);
    setBusy(root, trigger, true);

    const isSetup = mode === 'setup';

    postJson(beginUrl, csrfToken, {})
      .then(function (body) {
        const token = body.token;
        if (isSetup) {
          return navigator.credentials
            .create({ publicKey: toCreationOptions(body.credentialCreationOptions) })
            .then(function (credential) {
              return postJson(finishUrl, csrfToken, {
                token: token,
                credential: serializeCredential(credential),
                name: readName(root)
              });
            });
        }
        return navigator.credentials
          .get({ publicKey: toRequestOptions(body.credentialRequestOptions) })
          .then(function (credential) {
            return postJson(finishUrl, csrfToken, {
              token: token,
              credential: serializeCredential(credential),
              remember: readRemember(root)
            });
          });
      })
      .then(function () {
        window.location.assign(redirect);
      })
      .catch(function (error) {
        setBusy(root, trigger, false);
        showError(root, friendlyError(error));
      });
  }

  /**
   * @param {HTMLElement} root
   * @returns {string|null}
   */
  function readName(root) {
    const input = root.querySelector('.js-webauthn-name');
    if (!input || !input.value) {
      return null;
    }
    return input.value.trim() || null;
  }

  /**
   * @param {HTMLElement} root
   * @returns {boolean}
   */
  function readRemember(root) {
    const input = root.querySelector('.js-webauthn-remember');
    return !!(input && input.checked);
  }

  /**
   * Toggle the ceremony busy state: while the browser prompt is up (and during the finish request)
   * the trigger reflects that we are waiting for the authenticator, so the page does not look frozen.
   * The busy label is provided (and localized) by the template through the data-busy-label attribute.
   * @param {HTMLElement} root
   * @param {HTMLButtonElement} trigger
   * @param {boolean} busy
   */
  function setBusy(root, trigger, busy) {
    trigger.disabled = busy;
    trigger.setAttribute('aria-busy', busy ? 'true' : 'false');
    const busyLabel = trigger.getAttribute('data-busy-label') || 'Waiting for your security key…';
    const status = root.querySelector('.js-webauthn-status');
    if (status && !status.hasAttribute('data-idle-text')) {
      status.setAttribute('data-idle-text', status.textContent.trim());
    }
    if (busy) {
      if (!trigger.hasAttribute('data-idle-label')) {
        trigger.setAttribute('data-idle-label', trigger.textContent.trim());
      }
      trigger.textContent = busyLabel;
      trigger.classList.add('processing');
      if (status) {
        status.textContent = busyLabel;
        status.classList.add('js-webauthn-status--busy');
      }
    } else {
      const idleLabel = trigger.getAttribute('data-idle-label');
      if (idleLabel) {
        trigger.textContent = idleLabel;
      }
      trigger.classList.remove('processing');
      if (status) {
        const idleText = status.getAttribute('data-idle-text');
        if (idleText) {
          status.textContent = idleText;
        }
        status.classList.remove('js-webauthn-status--busy');
      }
    }
  }

  function disableTrigger(root) {
    const trigger = root.querySelector('.js-webauthn-trigger');
    if (trigger) {
      trigger.disabled = true;
    }
  }

  function showError(root, message) {
    const el = root.querySelector('.js-webauthn-error');
    if (el) {
      el.textContent = message;
      el.classList.remove('hidden');
    }
  }

  function clearError(root) {
    const el = root.querySelector('.js-webauthn-error');
    if (el) {
      el.textContent = '';
      el.classList.add('hidden');
    }
  }

  function boot() {
    const root = document.getElementById('webauthn-ceremony');
    if (root) {
      init(root);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
