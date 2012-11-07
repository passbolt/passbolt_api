steal(
	'./nested_plugin_xyz.css',
	function(){

        (init_nested_plugin_xyz = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("nested_plugin_xyz");
        })();

	});