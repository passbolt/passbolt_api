if (window.jasmine){
	steal('funcunit/browser/adapters/jasmine.js')
} else {
	steal('funcunit/browser/adapters/qunit.js')
}