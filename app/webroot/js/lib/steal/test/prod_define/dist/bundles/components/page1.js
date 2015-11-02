/*components/page1*/
define('components/page1', [
    'exports',
    'module',
    'jqwerty',
    'jqwertyui'
], function (exports, module, _jqwerty, _jqwertyui) {
    'use strict';
    var _interopRequire = function (obj) {
        return obj && obj.__esModule ? obj['default'] : obj;
    };
    var jqwerty = _interopRequire(_jqwerty);
    var jqwertyui = _interopRequire(_jqwertyui);
    console.log('page1.js loaded.');
	window.jqwerty = jqwerty;
    module.exports = {};
});
