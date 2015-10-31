import "steal-mocha";
import chai from "chai";
import mad from "mad";

// Define the global context.
var glbl = typeof window !== "undefined" ? window : global;

// Extract the expect & assert functions from chai and make them global
glbl.expect = chai.expect;
glbl.assert = chai.assert;

// Initialize a test namespace.
mad.test = mad.test || {};

// Make a global reference to the root reference element.
glbl.$rootElement = $('#test-html');
