(function () {
    var form = document.getElementById('webauthn-verify-form');
    if (!form) { return; }
    var redirect = form.getAttribute('data-redirect') || '/';

    var b64urlToBuf = function (s) {
        var pad = '='.repeat((4 - (s.length % 4)) % 4);
        var bin = atob((s + pad).replace(/-/g, '+').replace(/_/g, '/'));
        var buf = new Uint8Array(bin.length);
        for (var i = 0; i < bin.length; i++) { buf[i] = bin.charCodeAt(i); }
        return buf.buffer;
    };
    var bufToB64url = function (buf) {
        var bin = '';
        var arr = new Uint8Array(buf);
        for (var i = 0; i < arr.length; i++) { bin += String.fromCharCode(arr[i]); }
        return btoa(bin).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    };
    var csrf = function () {
        var m = document.cookie.split(';').map(function (c) { return c.trim().split('='); })
            .find(function (kv) { return kv[0] === 'csrfToken'; });
        return m ? m[1] : '';
    };
    var api = function (path, body, method) {
        return fetch(path, {
            method: method || 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': csrf()
            },
            body: body === undefined ? undefined : JSON.stringify(body || {})
        }).then(function (r) {
            return r.json().then(function (json) {
                if (!r.ok || (json.header && json.header.status !== 'success')) {
                    var err = new Error((json.header && json.header.message) || 'API request failed');
                    err.status = r.status;
                    err.body = json;
                    throw err;
                }
                return json.body;
            });
        });
    };

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        api('/mfa/verify/webauthn/begin.json?api-version=v2', {}).then(function (begin) {
            var opts = begin.credentialRequestOptions;
            var publicKey = Object.assign({}, opts, {
                challenge: b64urlToBuf(opts.challenge),
                allowCredentials: (opts.allowCredentials || []).map(function (d) {
                    return Object.assign({}, d, { id: b64urlToBuf(d.id) });
                })
            });
            return navigator.credentials.get({ publicKey: publicKey }).then(function (cred) {
                var rememberEl = document.getElementById('webauthn-remember');
                return api('/mfa/verify/webauthn/finish.json?api-version=v2', {
                    handle: begin.handle,
                    credential: {
                        id: cred.id,
                        rawId: bufToB64url(cred.rawId),
                        type: cred.type,
                        response: {
                            clientDataJSON: bufToB64url(cred.response.clientDataJSON),
                            authenticatorData: bufToB64url(cred.response.authenticatorData),
                            signature: bufToB64url(cred.response.signature),
                            userHandle: cred.response.userHandle
                                ? bufToB64url(cred.response.userHandle) : null
                        }
                    },
                    remember: !!(rememberEl && rememberEl.checked)
                });
            });
        }).then(function () {
            window.location.assign(redirect);
        }).catch(function (err) {
            console.error('WebAuthn verify failed', err);
        });
    });
})();
