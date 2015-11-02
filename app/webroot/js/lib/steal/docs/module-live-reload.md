@module {function()} steal.live-reload live-reload
@parent StealJS.modules
@description
Live-reload is a module that enables a speedier development workflow. Paired with a WebSocket server such as StealTools, `live-reload` will reload modules as you change them in your browser.

See [steal.live-reload.options] for a full set of configuration options that can be provided.

@signature `reload(callback)`

Reloads your application after a full reload cycle is complete.

```
import reload from "live-reload";

// Re-render your application after each reload.
reload(function(){
	render();
});
```

@param {Function} callback A function to be called after a reload cycle is complete.

@signature `reload(moduleName, callback)`

Observe reloading a specific module. Use this if the module creates side effects that need to be re-inited.

```
import reload from "live-reload";

// Re-initialize the router.
reload("app/router", function(router){
	window.router = router;
	router.start();
});
```

@param {String} moduleName the name of the module observing.
@param {function(value)} callback A function to be called when a specific module reloads. Is called with the new value.

@signature `reload("*", callback)`

Observe every module as they are reloaded.

@param {String} star The string `"*"` denotes that all module names will be observed.
@param {function(moduleName, moduleValue)} callback A function that will be called for each module as it is reloaded, with the `moduleName` and new `moduleValue` provided.

@signature `reload.dispose(callback)`

Observe the disposal of the current module. Used when the module has side-effects such as setting properties on the `window` that need to be removed before the module is reloaded.

```
import reload from "live-reload";

window.App = {};

reload.dispose(function(){
	delete window.App;
});
```

@param {function()} callback Function called before the module is deleted from the registry.

@body

## Use

Use live-reload by including it as a configDependency in your `package.json`:

```json
{
  "system": {
    "configDependencies": [
      "live-reload"
    ]
  }
}
```

Use [steal-tools] to start a live-reload WebSocket server.

```
steal-tools live-reload
```

Then launch your browser. **live-reload** will connect with the server and modules you change in your text editor will automatically be re-loaded. See the signatures above to understand how to use live-reload to observe reloads and act accordingly.

Most types of applications will need to re-render after a reload cycle. The following example shows this:

```js
import reload from "live-reload";
import template from "./template.stache!";

function render() {
	$("#app").html(template());
}

// Do the initial render.
render();

// Assign a callback that will be called whenever a reload cycle is complete.
// Call `render` again so that any code that changed code can take effect.
reload(function(){
	render();
});

```
