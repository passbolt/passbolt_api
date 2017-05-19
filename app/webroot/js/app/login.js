/**
 * The passbolt login application.
 */
import 'mad/mad';
import 'mad/bootstrap';
import passbolt from 'app/util/util';
import 'app/component/login';

var options = {
	app: {
		controllerElt: "#container",
		namespace: "passbolt",
		ControllerClassName: "passbolt.component.Login"
	}
};
mad.Config.load(options);

// Start the passbolt login application.
new mad.Bootstrap();