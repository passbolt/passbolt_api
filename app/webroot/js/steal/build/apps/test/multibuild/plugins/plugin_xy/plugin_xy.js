steal(
	'./plugin_xy.css',
    'steal/build/apps/test/multibuild/plugins/nested_plugin_xyz/nested_plugin_xyz.js',

	function(){

        (init_plugin_xy = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("plugin_xy");
        })();
		
	});