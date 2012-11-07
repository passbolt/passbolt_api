steal(
    './app_x.css',
    'steal/build/apps/test/multibuild/plugins/plugin_xy/plugin_xy.js',

    function(){

        (init_app_x = function() {
            if (typeof(modulesLoaded) == "undefined") {
                modulesLoaded = [];
            }
            modulesLoaded.push("app_x");
        })();

    });