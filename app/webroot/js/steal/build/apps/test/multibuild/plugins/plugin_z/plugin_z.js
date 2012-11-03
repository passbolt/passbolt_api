steal(
    './plugin_z.css',

    function(){

        (init_plugin_z = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("plugin_z");
        })();

    });