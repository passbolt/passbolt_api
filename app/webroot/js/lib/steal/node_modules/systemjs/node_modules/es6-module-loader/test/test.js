
var System, Loader, Module, tests, test;

var testCnt = 0, passed = 0, failed = 0;
var test = function(name, initialize) {
  if (typeof initialize != 'function') {
    var val = initialize;
    var exp = arguments[2];
    initialize = function(assert) {
      assert(val, exp);
    }
  }
  var testId = testCnt++;
  tests.addTest(testId, name);
  function assert(value, expected) {
    if (value != expected)
      return 'Got "' + value + '" instead of "' + expected + '"';
  }
  initialize(function(value, expected) {
    var failure;
    if (value instanceof Array) {
      for (var i = 0; i < arguments.length; i++)
        failure = failure || assert(arguments[i][0], arguments[i][1]);
    }
    else
      failure = assert(value, expected);
    if (failure)
      failed++;
    else
      passed++;
    tests.completeTest(testId, name, failure, { passed: passed, failed: failed, total: testCnt });
  }, function(err) {
    setTimeout(function() {
      throw err;
    });
  });
}

if (typeof window != 'undefined') {
  // browser
  document.body.innerHTML = "<table class='test'><tbody></tbody><td>Summary</td><td class='summary'></td></table>";
  tests = {
    addTest: function(id, name) {
      var p = document.createElement('tr');
      var td = document.createElement('td');
      td.innerHTML = name;
      p.appendChild(td);
      td = document.createElement('td');
      td.className = 'result-' + id;
      p.appendChild(td);
      document.querySelector('.test tbody').appendChild(p);
    },
    completeTest: function(id, name, failure, summary) {
      document.querySelector('.test .result-' + id).innerHTML = !failure ? 'Passed' : 'Failed: ' + failure;
      document.querySelector('.summary').innerHTML = summary.passed + '/' + summary.total + ' tests passed';
    }
  }
  window.test = test;
  window.runTests = runTests;
}
else {
  // nodejs
  var ml = require('../lib/index-' + process.env.es6compiler);

  if (process.env.es6compiler == '6to5')
    require('regenerator/runtime');

  process.on('uncaughtException', function(err) {
    console.log('Caught: ' + err);
  });

  System = ml.System;
  Loader = ml.Loader;
  Module = ml.Module;

  tests = {
    addTest: function(id, name) {},
    completeTest: function(id, name, failure, summary) {
      console.log(name + ': ' + (!failure ? 'Passed' : 'Failed: ' + failure));
      console.log(summary.passed + '/' + summary.total + ' passed. ');
      if (failure)
        process.exit(1);
    },
  };

  runTests();
}

function runTests() {

  // Normalize tests - identical to https://github.com/google/traceur-compiler/blob/master/test/unit/runtime/System.js

  var oldBaseURL = System.baseURL;
  System.baseURL = 'http://example.org/a/b.html';

  test('Normalize - No Referer', System.normalize('d/e/f'), 'd/e/f');
  // test('Normalize - Below baseURL', System.normalize('../e/f'), '../e/f');

  var refererName = 'dir/file';
  test('Normalize - Relative paths', System.normalize('./d/e/f', refererName), 'dir/d/e/f');
  test('Normalize - Relative paths', System.normalize('../e/f', refererName), 'e/f');

  test('Normalize - name undefined', function(assert) {
    try {
      System.normalize(undefined, refererName);
    }
    catch(e) {
      assert(e.message, 'Module name must be a string');
    }
  });

  test('Normalize - embedded ..', function(assert) {
    try {
      System.normalize('a/b/../c');
    }
    catch(e) {
      assert(e.message, 'Illegal module name "a/b/../c"');
    }
  });
  test('Normalize - embedded ..', function(assert) {
    try {
      System.normalize('a/../b', refererName);
    }
    catch(e) {
      assert(e.message, 'Illegal module name "a/../b"');
    }
  });
  test('Normalize - embedded ..', function(assert) {
    try {
      System.normalize('a/b/../c', refererName);
    }
    catch(e) {
      assert(e.message, 'Illegal module name "a/b/../c"');
    }
  });

  // test('Normalize - below referer', System.normalize('../../e/f', refererName), '../e/f');

  test('Normalize - backwards compat', System.normalize('./a.js'), 'a.js');

  test('Normalize - URL', function(assert) {
    try {
      System.normalize('http://example.org/a/b.html');
    }
    catch(e) {
      assert();
    }
  });

  System.baseURL = 'http://example.org/a/';

  test('Locate', System.locate({ name: '@abc/def' }), 'http://example.org/a/@abc/def.js');
  test('Locate', System.locate({ name: 'abc/def' }), 'http://example.org/a/abc/def.js');

  // paths
  System.paths['path/*'] = '/test/*.js';
  test('Locate paths', System.locate({ name: 'path/test' }), 'http://example.org/test/test.js');


  System.baseURL = oldBaseURL;



  // More Normalize tests

  test('Normalize test 1', function(assert) {
    assert(System.normalize('./a/b', 'c'), 'a/b');
  });
  test('Normalize test 2', function(assert) {
    assert(System.normalize('./a/b', 'c/d'), 'c/a/b');
  });
  test('Normalize test 3', function(assert) {
    assert(System.normalize('./a/b', '../c/d'), '../c/a/b');
  });
  test('Normalize test 4', function(assert) {
    assert(System.normalize('./a/b', '../c/d'), '../c/a/b');
  });
  test('Normalize test 5', function(assert) {
    assert(System.normalize('../a/b', '../../c/d'), '../../a/b');
  });

  test('Setting & deleting modules', function(assert, err) {
    System['import']('loader/module').then(function(m1) {
      System['delete']('loader/module');
      System['import']('loader/module').then(function(m2) {
        System['delete']('loader/module');
        System.set('loader/module', System.newModule({custom: 'module'}));
        System['import']('loader/module').then(function(m3) {
          assert(
            [m1.run, 'first'],
            [m2.run, 'second'],
            [m3.custom, 'module']
          );
        }, err);
      }, err);
    }, err);
  });

  test('Import a script', function(assert, err) {
    System['import']('syntax/script').then(function(m) {
      assert(!!m, true);
    }, err);
  });

  test('Import a script once loaded', function(assert, err) {
    System['import']('syntax/script').then(function(m) {
      System['import']('syntax/script').then(function(m) {
        assert(!!m, true);
      }, err);
    });
  });

  test('Import ES6', function(assert, err) {
    System['import']('syntax/es6').then(function(m) {
      assert(m.p, 'p');
    }, err);
  });

  test('Import ES6 with dep', function(assert, err) {
    System['import']('syntax/es6-withdep').then(function(m) {
      assert(m.p, 'p');
    }, err);
  });

  test('Import ES6 Generator', function(assert, err) {
    System['import']('syntax/es6-generator').then(function(m) {
      assert(!!m.generator, true);
    }, err);
  });

  test('Direct import without bindings', function(assert, err) {
    System['import']('syntax/direct').then(function(m) {
      assert(!!m, true);
    }, err);
  });

  test('Circular Dependencies', function(assert, err) {
    System['import']('syntax/circular1').then(function(m1) {
      System['import']('syntax/circular2').then(function(m2) {
        assert(
          [m2.output, 'test circular 1'],
          [m1.output, 'test circular 2'],
          [m2.output1, 'test circular 2'],
          [m1.output2, 'test circular 1']
        );
      }, err);
    }, err);
  });

  test('Circular Test', function(assert, err) {
    System['import']('syntax/even').then(function(m) {
      assert(
        [m.even(10), true],
        [m.counter, 7],
        [m.even(15), false],
        [m.counter, 15]
      );
    }, err);
  });

  test('Load order test: A', function(assert, err) {
    System['import']('loads/a').then(function(m) {
      assert(
        [m.a, 'a'],
        [m.b, 'b']
      );
    }, err);
  });

  test('Load order test: C', function(assert, err) {
    System['import']('loads/c').then(function(m) {
      assert(
        [m.c, 'c'],
        [m.a, 'a'],
        [m.b, 'b']
      );
    }, err);
  });

  test('Load order test: S', function(assert, err) {
    System['import']('loads/s').then(function(m) {
      assert(
        [m.s, 's'],
        [m.c, 'c'],
        [m.a, 'a'],
        [m.b, 'b']
      );
    }, err);
  });

  test('Load order test: _a', function(assert) {
    System['import']('loads/_a').then(function(m) {
      assert(
        [m.b, 'b'],
        [m.d, 'd'],
        [m.g, 'g'],
        [m.a, 'a']
      );
    })
  });

  test('Load order test: _e', function(assert) {
    System['import']('loads/_e').then(function(m) {
      assert(
        [m.c, 'c'],
        [m.e, 'e']
      );
    })
  });
  
  test('Load order test: _f', function(assert) {
    System['import']('loads/_f').then(function(m) {
      assert(
        [m.g, 'g'],
        [m.f, 'f']
      );
    })
  });
  test('Load order test: _h', function(assert) {
    System['import']('loads/_h').then(function(m) {
      assert(
        [m.i, 'i'],
        [m.a, 'a'],
        [m.h, 'h']
      );
    })
  });

  test('Error check 1', function(assert) {
    System['import']('loads/main').then(function(m) {
      assert(false, true);
    }, function(e) {
      assert(e, 'Error evaluating loads/deperror\ndep error');
    });
    // System['import']('loads/deperror');
  });

  test('Unhandled rejection test', function(assert) {
    System['import']('loads/load-non-existent')
    assert();
  });


  test('Export Syntax', function(assert) {
    System['import']('syntax/export').then(function(m) {
      assert(
        [m.p, 5],
        [typeof m.foo, 'function'],
        [typeof m.q, 'object'],
        [typeof m['default'], 'function'],
        [m.s, 4],
        [m.t, 4],
        [typeof m.m, 'object']
      );
    });
  });

  // test not enabled for Babel
  if (System.transpiler != 'babel')
  test('Export Star 2', function(assert) {
    System['import']('syntax/export-star2').then(function(m) {
      assert(
        [typeof m.foo, 'function'],
        [m.bar, 'bar']
      );
    });
  });

  test('Export Star', function(assert) {
    System['import']('syntax/export-star').then(function(m) {
      assert(
        [m.foo, 'foo'],
        [m.bar, 'bar']
      );
    });
  });

  test('Export default 1', function(assert, err) {
    System['import']('syntax/export-default').then(function(m) {
      assert(m['default'](), 'test');
    }, err);
  });

  test('Re-export', function(assert, err) {
    System['import']('syntax/reexport1').then(function(m) {
      assert(m.p, 5);
    }, err);
  });

  test('Re-export with new name', function(assert, err) {
    System['import']('syntax/reexport2').then(function(m) {
      assert(
        [m.q, 4],
        [m.z, 5]
      );
    }, err);
  });

  test('Re-export binding', function(assert, err) {
    System['import']('syntax/reexport-binding').then(function(m) {
      System['import']('syntax/rebinding').then(function(m) {
        assert(m.p, 4);
      });
    }, err);
  });

  test('Import Syntax', function(assert, err) {
    System['import']('syntax/import').then(function(m) {
      assert(
        [typeof m.a, 'function'],
        [m.b, 4],
        [m.c, 5],
        [m.d, 4],
        [typeof m.q.foo, 'function']
      );
    }, err);
  });

  test('ES6 Syntax', function(assert, err) {
    System['import']('syntax/es6-file').then(function(m) {
      setTimeout(function() {
        (new m.q()).foo();
      });
      assert(
        [typeof m.q, 'function']
      );
    }, err);
  });

  test('Module Name meta', function(assert) {
    System['import']('loader/moduleName').then(function(m) {
      assert(
        [m.name, 'loader/moduleName'],
        [m.address, System.baseURL + 'loader/moduleName.js']
      );
    });
  });

  test('Custom path', function(assert) {
    System.paths['bar'] = 'loader/custom-path.js';
    System['import']('bar').then(function(m) {
      assert(m.bar, 'bar');
    })
  });

  test('Custom path wildcard', function(assert) {
    System.paths['bar/*'] = 'loader/custom-folder/*.js';
    System['import']('bar/path').then(function(m) {
      assert(m.bar, 'baa');
    });
  });

  test('Custom path most specific', function(assert) {
    delete System.paths['bar/*'];
    System.paths['bar/bar'] = 'loader/specific-path.js';
    System.paths['bar/*'] = 'loader/custom-folder/*.js';
    System['import']('bar/bar').then(function(m) {
      assert(m.path, true);
    });
  });

  test('should load System.define', function(assert) {
    var oldLocate = System.locate;
    var slaveLocatePromise = new Promise(function(resolve, reject) {

      System.locate = function(load) {
        if(load.name === 'slave') {
          setTimeout(function() {
            System.define('slave', 'var double = [1,2,3].map(i => i * 2);');
            resolve('slave.js');
          }, 1);
          return slaveLocatePromise;
        }
        return oldLocate.apply(this, arguments);
      };

    });

    System.import('loader/master').then(function() {
      assert(true, true, 'Able to load');
    }, function(err) {
      assert('Did not resolve');
    }).then(reset, reset);

    function reset() {
      System.locate = oldLocate;
    }
  });

  var customModules = {};
  var customFactories = {};

  var executeModule = function(name) {
    if (!customFactories[name])
      return;
    var module = customFactories[name].apply(null, []);
    customModules[name] = module;
    return module;
  }

  var customLoader = new Reflect.Loader({
    normalize: function(name, parentName, parentAddress) {
      return new Promise(function(resolve, reject) {
        if (name == 'asdfasdf') {
          return setTimeout(function() {
            resolve('loader/async-norm');
          }, 500);
        }

        if (name == 'error1')
          return setTimeout(function(){ reject('error1'); }, 100);

        var normalized = System.normalize(name, parentName, parentAddress);
        resolve(normalized);
      });
    },
    locate: function(load) {
        if (load.name == 'error2')
          return new Promise(function(resolve, reject) {
            setTimeout(function(){ reject('error2'); }, 100);
          });

      if (load.name.substr(0, 5) == 'path/')
        load.name = 'loader/' + load.name.substr(5);
      return System.locate(load);
    },
    fetch: function(load) {
      if (load.name == 'error3')
        throw 'error3';
      if (load.name == 'error4' || load.name == 'error5')
        return 'asdf';
      return System.fetch.apply(this, arguments);
    },
    translate: function(load) {
      if (load.name == 'error4')
        return new Promise(function(resolve, reject) {
          setTimeout(function(){ reject('error4'); }, 100);
        });
      return System.translate.apply(this, arguments);
    },
    instantiate: function(load) {
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

      if (load.name == 'error5')
        return new Promise(function(resolve, reject) {
          setTimeout(function(){ reject('error5'); }, 100);
        });
      // very bad AMD support
      if (load.source.indexOf('define') == -1)
        return System.instantiate(load);

      var factory, deps;
      var define = function(_deps, _factory) {
        deps = _deps;
        factory = _factory;
      }
      //console.log(load.source);
      eval(load.source);

      customFactories[load.name] = factory;

      // normalize all dependencies now
      var normalizePromises = [];
      for (var i = 0; i < deps.length; i++)
        normalizePromises.push(Promise.resolve(System.normalize(deps[i], load.name)));

      return Promise.all(normalizePromises).then(function(resolvedDeps) {

        return {
          deps: deps,
          execute: function() {
            if (customModules[load.name])
              return System.newModule(customModules[load.name]);

            // first ensure all dependencies have been executed
            for (var i = 0; i < resolvedDeps.length; i++)
              resolvedDeps[i] = executeModule(resolvedDeps[i]);

            var module = factory.apply(null, resolvedDeps);

            customModules[load.name] = module;
            return System.newModule(module);
          }
        };
      });
    }
  });
  customLoader.transpiler = System.transpiler;

  test('Custom loader standard load', function(assert) {
    var p = customLoader['import']('loader/test').then(function(m) {
      assert(m.loader, 'custom');
    });
    if (p['catch'])
      p['catch'](function(e) {
        assert(!e, 'standard load failed: ' + e);
      });
  });

  test('Custom loader special rules', function(assert) {
    var p = customLoader['import']('path/custom').then(function(m) {
      assert(m.path, true);
    });
    if (p['catch'])
      p['catch'](function(e) {
        assert(!e, 'special rules failed: ' + e);
      });
  });

  test('Custom loader AMD support', function(assert) {
    customLoader['import']('loader/amd').then(function(m) {
      assert(m.format, 'amd');
    })['catch'](function(e) {
      setTimeout(function() {
        throw e;
      }, 1);
    });
  });

  test('Custom loader hook - normalize error', function(assert) {
    customLoader['import']('loader/error1-parent').then(function(m) {
    })['catch'](function(e) {
      assert(e.toString(), 'Error loading "loader/error1-parent" at ' + System.baseURL + 'loader/error1-parent.js\nerror1');
    });
  });
  test('Custom loader hook - locate error', function(assert) {
    customLoader['import']('error2').then(function(m) {}, function(e) {
      assert(e.toString(), 'Error loading "error2" at <unknown>\nerror2');
    });
  });
  test('Custom loader hook - fetch error', function(assert) {
    customLoader['import']('error3').then(function(m) {}, function(e) {
      assert(e.toString(), 'Error loading "error3" at ' + System.baseURL + 'error3.js\nerror3');
    });
  });
  test('Custom loader hook - translate error', function(assert) {
    customLoader['import']('error4').then(function(m) {}, function(e) {
      assert(e.toString(), 'Error loading "error4" at ' + System.baseURL + 'error4.js\nerror4');
    });
  });
  test('Custom loader hook - instantiate error', function(assert) {
    customLoader['import']('error5').then(function(m) {}, function(e) {
      assert(e.toString(), 'Error loading "error5" at ' + System.baseURL + 'error5.js\nerror5');
    });
  });

  test('Async Normalize', function(assert) {
    customLoader.normalize('asdfasdf').then(function(normalized) {
      return customLoader['import'](normalized);
    }).then(function(m) {
      assert(m.n, 'n');
    });
  });

  test('System instanceof Loader', function(assert) {
    assert(System instanceof Reflect.Loader, true);
  });

  if (typeof Worker != 'undefined')
  test('Loading inside of a Web Worker', function(assert) {
    var worker = new Worker('worker/worker-' + System.transpiler + '.js');

    worker.onmessage = function(e) {
      assert(e.data, 'p');
    };
  });
}
