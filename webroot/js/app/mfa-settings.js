function readCookie(name) {
  const value = '; ' + document.cookie;
  const parts = value.split('; ' + name + '=');
  if (parts.length === 2) return parts.pop().split(';').shift();
}
function domReady() {
  const totpDisableButton = document.getElementById('js_totp_disable');
  const bases = document.getElementsByTagName('base');
  const appUrl = bases[0].getAttribute('href');
  totpDisableButton.addEventListener('click', () => {
    totpDisableButton.classList.add('processing');
    return fetch(appUrl + '/mfa/setup/totp.json', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'X-CSRF-Token': readCookie('csrfToken')
      },
      redirect: 'follow'
    }).then(() => {
      window.location.replace(window.location.href);
    }, () => {
      totpDisableButton.classList.remove('processing');
    })
  }, false);
}
document.addEventListener( 'DOMContentLoaded', function(){
  document.removeEventListener( 'DOMContentLoaded', arguments.callee, false);
  domReady();
}, false );
