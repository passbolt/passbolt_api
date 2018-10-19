function readCookie(name) {
  const value = '; ' + document.cookie;
  const parts = value.split('; ' + name + '=');
  if (parts.length === 2) return parts.pop().split(';').shift();
}
function domReady() {
  const totpDisableButton = document.getElementById('js_mfa_provider_disable');
  totpDisableButton.addEventListener('click', () => {
    totpDisableButton.classList.add('processing');
    const provider = totpDisableButton.dataset.provider;
    const url = `/mfa/setup/${provider}.json`;
    console.log(url);
    return fetch(url, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'X-CSRF-Token': readCookie('csrfToken')
      },
      redirect: 'follow'
    }).then(() => {
      window.location.replace('/mfa/setup/select');
    }, () => {
      totpDisableButton.classList.remove('processing');
    })
  }, false);
}
document.addEventListener( 'DOMContentLoaded', function(){
  document.removeEventListener( 'DOMContentLoaded', arguments.callee, false);
  domReady();
}, false );
