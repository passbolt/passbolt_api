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
 * Top-level passkey ceremony. Served from the passbolt origin (opened by the extension
 * as a popup) so navigator.credentials.* binds to the correct relying party id — WebAuthn cannot run
 * in the extension's cross-origin app iframe. It fetches the options ("begin"), runs the WebAuthn
 * call, DEPOSITS the raw credential onto the challenge token, then navigates to the completion url the
 * extension watches. The extension then collects the credential and finishes (kit + GPGAuth).
 */
(function () {
  'use strict';

  // Pending "show error then hand back to the extension" timer, cancelled if the user retries.
  let errorNavTimer = null;

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

  function bufferToBase64Url(buffer) {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  }

  function decodeDescriptors(descriptors) {
    return (descriptors || []).map(function (descriptor) {
      return Object.assign({}, descriptor, { id: base64UrlToBuffer(descriptor.id) });
    });
  }

  function toCreationOptions(options) {
    const publicKey = Object.assign({}, options);
    publicKey.challenge = base64UrlToBuffer(options.challenge);
    publicKey.user = Object.assign({}, options.user, { id: base64UrlToBuffer(options.user.id) });
    publicKey.excludeCredentials = decodeDescriptors(options.excludeCredentials);
    return publicKey;
  }

  function toRequestOptions(options) {
    const publicKey = Object.assign({}, options);
    publicKey.challenge = base64UrlToBuffer(options.challenge);
    publicKey.allowCredentials = decodeDescriptors(options.allowCredentials);
    return publicKey;
  }

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
      serialized.response.attestationObject = bufferToBase64Url(response.attestationObject);
      if (typeof response.getTransports === 'function') {
        serialized.response.transports = response.getTransports();
      }
    } else {
      serialized.response.authenticatorData = bufferToBase64Url(response.authenticatorData);
      serialized.response.signature = bufferToBase64Url(response.signature);
      serialized.response.userHandle = response.userHandle ? bufferToBase64Url(response.userHandle) : null;
    }
    return serialized;
  }

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

  function showError(root, message) {
    const el = root.querySelector('.js-passkey-error');
    if (el) {
      el.textContent = message;
      el.classList.remove('hidden');
    }
    const status = root.querySelector('.js-passkey-status');
    if (status) {
      status.textContent = message;
    }
  }

  function hideError(root) {
    if (errorNavTimer) {
      window.clearTimeout(errorNavTimer);
      errorNavTimer = null;
    }
    const el = root.querySelector('.js-passkey-error');
    if (el) {
      el.classList.add('hidden');
      el.textContent = '';
    }
    root.classList.remove('processing');
  }

  // Deposit the raw credential onto the challenge token, then navigate to the completion url the
  // extension watches. Shared by setup, scoped login and the discoverable ("another method") login.
  function depositAndComplete(ctx, credential) {
    return postJson('/passkey/deposit.json', ctx.csrfToken, {
      user_id: ctx.userId,
      token: ctx.token,
      mode: ctx.mode,
      credential: serializeCredential(credential)
    }).then(function () {
      const params = 'token=' + encodeURIComponent(ctx.token) + '&mode=' + encodeURIComponent(ctx.mode) +
        '&user_id=' + encodeURIComponent(ctx.userId);
      window.location.assign('/passkey/ceremony/complete?' + params);
    });
  }

  // Show the error for a few seconds so the user can read it, then hand control back to the extension
  // (it watches the completion url) with an error flag. That rejects the pending login/enroll so the
  // parent falls back to the passphrase form instead of leaving the user stuck on "processing".
  function handleError(ctx, error) {
    showError(ctx.root, (error && error.message) ? error.message : 'Something went wrong. Please try again.');
    if (errorNavTimer) {
      window.clearTimeout(errorNavTimer);
    }
    errorNavTimer = window.setTimeout(function () {
      const params = 'error=1&mode=' + encodeURIComponent(ctx.mode) + '&user_id=' + encodeURIComponent(ctx.userId || '');
      window.location.assign('/passkey/ceremony/complete?' + params);
    }, 3500);
  }

  // Run a login assertion against the user's registered keys. This is passbolt's own passkey
  // login (native/direct) — no "use another method" escape hatch here; that only belongs to the
  // third-party passkey provider ceremony.
  function runLoginAssertion(ctx) {
    const publicKey = toRequestOptions(ctx.options);
    return navigator.credentials.get({ publicKey: publicKey })
      .then(function (credential) { return depositAndComplete(ctx, credential); })
      .catch(function (error) { handleError(ctx, error); });
  }

  function run(root) {
    if (!window.PublicKeyCredential || !navigator.credentials) {
      showError(root, 'This browser does not support security keys.');
      return;
    }

    const mode = root.getAttribute('data-mode');
    const userId = root.getAttribute('data-user-id');
    const csrfToken = root.getAttribute('data-csrf-token');
    const isSetup = mode === 'setup';
    const beginUrl = isSetup ? '/passkey/setup/begin.json' : '/passkey/login/begin.json';
    const beginPayload = isSetup ? {} : { user_id: userId };

    postJson(beginUrl, csrfToken, beginPayload)
      .then(function (body) {
        const ctx = { root: root, mode: mode, userId: userId, csrfToken: csrfToken, token: body.token };
        if (isSetup) {
          return navigator.credentials.create({ publicKey: toCreationOptions(body.credentialCreationOptions) })
            .then(function (credential) { return depositAndComplete(ctx, credential); })
            .catch(function (error) { handleError(ctx, error); });
        }
        ctx.options = body.credentialRequestOptions;
        return runLoginAssertion(ctx);
      })
      .catch(function (error) {
        handleError({ root: root, mode: mode, userId: userId }, error);
      });
  }

  function boot() {
    const root = document.getElementById('passkey-ceremony');
    if (root) {
      run(root);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
