import $ from "jquery";
import "mad/bootstrap";
import appConfig from "js/app/bootstrap/config.json";
import "js/app/bootstrap/app";

// Load the application config.
mad.Config.load(appConfig);
// Start the framework and the application.
var bootstrap = new mad.Bootstrap();
