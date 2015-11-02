//

(function (__global) {


  var customModules = {};
  var customFactories = {};

  var executeModule = function (name) {
    if (!customFactories[name]) {
      return;
    }
    var module = customFactories[name].apply(null, []);
    customModules[name] = module;
    return module;
  };

  var customLoader = new Reflect.Loader({
    normalize: function (name, parentName, parentAddress) {
      return new Promise(function (resolve, reject) {
        if (name == 'asdfasdf') {
          return setTimeout(function () {
            resolve('test/loader/async-norm');
          }, 500);
        }

        if (name == 'error1') {
          return setTimeout(function () { reject('error1'); }, 100);
        }

        var normalized = System.normalize(name, parentName, parentAddress);
        resolve(normalized);
      });
    },
    locate: function (load) {
      if (load.name == 'error2') {
        return new Promise(function (resolve, reject) {
          setTimeout(function () { reject('error2'); }, 100);
        });
      }

      if (load.name.substr(0, 5) == 'path/') {
        load.name = 'test/loader/' + load.name.substr(5);
      }
      return System.locate(load);
    },
    fetch: function (load) {
      if (load.name == 'error3') {
        throw 'error3';
      }
      if (load.name == 'error4' || load.name == 'error5') {
        return 'asdf';
      }
      return System.fetch.apply(this, arguments);
    },
    translate: function (load) {
      if (load.name == 'error4') {
        return new Promise(function (resolve, reject) {
          setTimeout(function () { reject('error4'); }, 100);
        });
      }
      return System.translate.apply(this, arguments);
    },
    instantiate: function (load) {
      if (load.name == this.transpiler) {
        var transpiler = this.transpiler;
        return System.import(transpiler).then(function() {
          return {
            deps: [],
            execute: function() {
              return System.get(transpiler);
            }
          };
        });
      }

      if (load.name == 'error5') {
        return new Promise(function (resolve, reject) {
          setTimeout(function () { reject('error5'); }, 100);
        });
      }
      // very bad AMD support
      if (load.source.indexOf('define') == -1) {
        return System.instantiate(load);
      }

      var factory, deps;
      var define = function (_deps, _factory) {
        deps = _deps;
        factory = _factory;
      };
      eval(load.source);

      customFactories[load.name] = factory;

      // normalize all dependencies now
      var normalizePromises = [];
      for (var i = 0; i < deps.length; i++) {
        normalizePromises.push(Promise.resolve(System.normalize(deps[i], load.name)));
      }

      return Promise.all(normalizePromises).then(function (resolvedDeps) {

        return {
          deps: deps,
          execute: function () {
            if (customModules[load.name]) {
              return System.newModule(customModules[load.name]);
            }

            // first ensure all dependencies have been executed
            for (var i = 0; i < resolvedDeps.length; i++) {
              resolvedDeps[i] = executeModule(resolvedDeps[i]);
            }

            var module = factory.apply(null, resolvedDeps);

            customModules[load.name] = module;
            return System.newModule(module);
          }
        };
      });
    }
  });

  customLoader.transpiler = System.transpiler;


  if (typeof exports === 'object')
    module.exports = customLoader;

  __global.customLoader = customLoader;
}(typeof window != 'undefined' ? window : global));
