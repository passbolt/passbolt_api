importScripts("../../node_modules/when/es6-shim/Promise.js",
             "../../dist/es6-module-loader.src.js");
System.paths['traceur'] = '../../node_modules/traceur/bin/traceur.js';
System['import']('es6').then(function(m) {
  postMessage(m.p);
}, function(err) {
  console.error(err, err.stack);
});
