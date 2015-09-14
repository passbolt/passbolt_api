window.passbolt = {};
import 'mad/bootstrap';
import appConfig from "app/config/config.json";
//import 'app/error/error_handler';

/**
 * @inherits mad.Bootstrap
 * @parent passbolt.core
 *
 * The Passbolt application bootstrap.
 */
mad.Config.load(appConfig);
passbolt.bootstrap = mad.Bootstrap.extend('passbolt.Bootstrap', {});
var bootstrap = new passbolt.Bootstrap();
