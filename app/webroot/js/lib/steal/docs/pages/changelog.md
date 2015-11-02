@page StealJS.changelog Changelog
@parent StealJS.guides

@body

## 0.10.0

### steal

- New [live-reload](http://stealjs.com/docs/steal.live-reload.html) extension.
- Added a [steal.import](http://stealjs.com/docs/steal.import.html) to make it easier to work in Node. [#407](https://github.com/stealjs/steal/issues/407).

### steal-tools

- Added a [new command](http://stealjs.com/docs/steal-tools.cmd.live-reload.html) for the cli, `steal-tools live-reload` which starts a server for use with the live-reload workflow. [#223](https://github.com/stealjs/steal-tools/pull/233).

## 0.9.0

### steal

- The npm plugin added a `configDependencies` option. [#55](https://github.com/stealjs/system-npm/pull/55).
- Steal can be launched within a web worker. [#386](https://github.com/stealjs/steal/issues/386).
- The bower plugin can take a `system.main` that mirrors npm's behavior. [#16](https://github.com/stealjs/system-bower/pull/16).
- The bower plugin supports `system.bowerIgnore` for ignoring modules. [#17](https://github.com/stealjs/system-bower/pull/17).
- You can now pass your own paths to `lessOptions`. [#378](https://github.com/stealjs/steal/pull/378).

### steal-tools

- Added a Watch Mode [#226](https://github.com/stealjs/steal-tools/pull/226) for multi-builds. See the [guide](http://stealjs.com/docs/steal-tools.guides.watch_mode.html) for usage.

## 0.8.0

- StealTools now produces source maps for both multi-build and export. [#210](https://github.com/stealjs/steal-tools/pull/210). Check out the [build docs](http://stealjs.com/docs/steal-tools.build.html) for example usage.
- The cli is now easier to use. The `package.json` is now the default config, so no `--config` or `--main` option is needed if using the npm plugin. [#212](https://github.com/stealjs/steal-tools/pull/212)
- Upgraded SystemJS to `0.16.6`, ES6 Module Loader to `0.16.3` and Traceur to `0.0.87`.

## 0.7.0

### steal

- [npm] and [bower] plugins can be used with each other using [configDependencies](http://stealjs.com/docs/npm.html)
(and [here](http://stealjs.com/docs/bower.html)).
- Updated SystemJS and ESML.
- Choice of ES6 compiler can be controlled through the [System.transpiler transpiler] config.
- [System.bundle] can now take a glob.
- Loading in Node on Windows no longer requires setting paths with `file:` prefix.
- Less plugin upgraded to use Less 2.4.0.

### steal-tools

- Bundles now get written to subdirectories of [System.bundlesPath bundlesPath] to ensure unique. [#52](https://github.com/bitovi/steal-tools/pull/54)
- All tests passing on Windows.
- `main` and `bundle` names can be the unnormalized. [#89](https://github.com/bitovi/steal-tools/issues/89).

      stealTools.build({
        main: "foo/bar/"
      });

## 0.6.0

### steal

- Added the [npm] extension.
- Add the [bower] extension.
- Updated SystemJS and ESML
- If _steal.js_ is found in node_modules, 
  load `package.json!npm` as [System.configMain].
- If _steal.js_ is found in bower_components, load
  `bower.json!bower` as [System.configMain].
- Replaced `@config` with [System.configMain]. If you were doing:
      
      System.import("@config")
      
  You should do:
  
      System.import(System.configMain)

### steal-tools


- Added [steal-tools/lib/build/helpers/amd],
  [steal-tools/lib/build/helpers/cjs] and
  [steal-tools/lib/build/helpers/global] export helpers.
- Grunt task `stealPluginify` is now `steal-export`
- Grunt task `stealBuild` is now `steal-build`.
- `stealTools.pluginifier` is now `steal.transform`.
- Command line `steal-tools pluginify` is now `steal tools transform`.
- [steal-tools.export], formerly the _lib/build/pluginifier_builder_ module
  now returns a deferred and the defaults and modules arguments have been switched.
