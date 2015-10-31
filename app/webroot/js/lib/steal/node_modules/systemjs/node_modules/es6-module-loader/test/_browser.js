
// Change base url to the karma "base"
System.baseURL += 'base/';

System.paths.traceur = 'node_modules/traceur/bin/traceur.js';
System.paths.babel = 'node_modules/babel-core/browser.js';

System.transpiler = __karma__.config.system.transpiler;
