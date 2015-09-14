import 'mad/bootstrap';
import passbolt from 'app/util/util'; // @todo rename to setting maybe.
import 'app/error/error_handler';
import 'app/component/app';
import appConfig from 'app/config/config.json';

/**
 * @parent passbolt.core
 *
 * The Passbolt application bootstrap.
 */

// Load the config packaged with the front-end application.
mad.Config.load(appConfig);

// Load the dynamic config served by the back-end.
console.log(cakephpConfig);
mad.Config.load(cakephpConfig);

// Start the application bootstrap.
var bootstrap = new mad.Bootstrap();
