steal(
    './plugin_yz.css',
    'steal/build/apps/test/multibuild/plugins/nested_plugin_xyz/nested_plugin_xyz.js',

    function(){

        (init_plugin_yz = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("plugin_yz");
        })();

    });