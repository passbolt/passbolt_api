/**
 * Implements shim support for steal
 *
 * This function sets up shims for steal. It follows RequireJS' syntax:
 *
 *     steal.config({
 *        shim : {
 *          jquery: {
 *            exports: "jQuery"
 *          }
 *        }
 *      })
 * 
 * You can also set function to explicitely return value from the module:
 *
 *     steal.config({
 *        shim : {
 *          jquery: {
 *            exports: function(){
 *              return window.jQuery;
 *            }
 *          }
 *        }
 *      })
 *
 * This enables steal to pass you a value from library that is not wrapped
 * with steal() call.
 *
 *     steal('jquery', function(j){
 *       // j is set to jQuery
 *     })
 */
st.setupShims = function(shims){
	// Go through all shims
	for(var id in shims){
		// Make resource from shim's id. Since steal takes care
		// of always returning same resource for same id 
		// when someone steals resource created in this function
		// they will get same object back
		var resource = Module.make({id: id});
		if(typeof shims[id] === "object"){
			// set up dependencies of the module
			var needs   = shims[id].deps || []
			var exports = shims[id].exports;
			var init    = shims[id].init
		} else {
			needs = shims[id];
		}
		(function(_resource, _needs){
			_resource.options.needs = _needs;
		})(resource, needs);
		// create resource's exports function. We check for existance
		// of this function in `Module.prototype.executed` and if it exitst
		// it is called, which sets `value` of the module 
		resource.exports = (function(_resource, _needs, _exports, _init){
			return function(){
				var args = [];
				h.each(_needs, function(i, id){
					args.push(Module.make(id).value);
				});
				if(_init){
					// if module has exports function, call it
					_resource.value = _init.apply(null, args);
				} else {
					// otherwise it's a string so we just return
					// object from the window e.g window['jQuery']
					_resource.value = h.win[_exports];
				}
			}
		})(resource, needs, exports, init)
	}
}