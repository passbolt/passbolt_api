MAD_ROOT = 'lib/mad';
APP_URL = 'http://passbolt.local';
steal(
    'funcunit',
    'jquery/dom/fixture',
    MAD_ROOT+'/mad.js'
)
.then(
    "./core/class.js",
    "./controller/controller.js",
    "./controller/appController.js",
    "./core/singleton.js",
    "./error/error.js",
    "./lang/i18n.js",
    "./net/ajax.js",
    "./string/uuid.js"
);