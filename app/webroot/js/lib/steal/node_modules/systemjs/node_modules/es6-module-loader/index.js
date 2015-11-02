var System = require('./dist/es6-module-loader.src');

System.transpiler = 'traceur';
try {
  System.paths.traceur = 'file:' + require.resolve('traceur/bin/traceur.js');
}
catch(e) {}
try {
  System.paths.babel = 'file:' + require.resolve('babel-core/browser.js');
}
catch(e) {}
try {
  System.paths.babel = System.paths.babel || 'file:' + require.resolve('babel/browser.js');
}
catch(e) {}

module.exports = {
  Loader: global.LoaderPolyfill,
  System: System
};
