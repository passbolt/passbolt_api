steal(
    './app_y.css',
    'steal/build/apps/test/multibuild/plugins/plugin_xy/plugin_xy.js',
    'steal/build/apps/test/multibuild/plugins/plugin_yz/plugin_yz.js',

    function(){

        (init_app_y = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("app_y");
        })();

    });