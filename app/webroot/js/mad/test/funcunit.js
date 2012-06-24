MAD_ROOT = 'lib/mad';
APP_URL = 'http://passbolt.local';
steal(
    'funcunit',
    'jquery/dom/fixture',
    MAD_ROOT+'/mad.js'
)
.then(
    "./error/error.js",
    "./core/singleton.js",
    "./lang/i18n.js"
//    "./net/ajax.js"
);