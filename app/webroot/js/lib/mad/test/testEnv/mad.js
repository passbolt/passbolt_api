/*
 * @page index Passbolt
 * @tag home
 *
 * ###Passbolt
 *  
 * Our Passbolt
 *  
 * * passbolt.passbolt.controller.PasswordWorkspaceController
 */

APP_URL = 'http://passbolt.local';
MAD_ROOT = 'lib/mad';

steal(MAD_ROOT + '/mad.js')
.then('jquery/plugin/jquery-ui-1.8.20.custom.min.js')
.then(function () {
	steal.options.logLevel = 0;
});