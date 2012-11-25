steal('can/util', 'can/route', function(can) {
    "use strict";

    if(window.history && history.pushState) {

        var getPath = function() {
            return location.pathname + location.search;
        };

        // popstate only fires on back/forward.
        // To detect when someone calls push/replaceState, we need to wrap each method.
        can.each(['pushState','replaceState'],function(method) {
            var orig = history[method];
            history[method] = function(state) {
                var result = orig.apply(history, arguments);
                can.route.history.attr('path',getPath());
                can.route.history.attr('type',method);
                return result;
            };
        });
        // Bind to popstate for back/forward
        can.bind.call(window, 'popstate', function() {
            can.route.history.attr('path',getPath());
            can.route.history.attr('type','popState');
        });


        var param = can.route.param,
            paramsMatcher = /^\?(?:[^=]+=[^&]*&)*[^=]+=[^&]*/;
        can.extend(can.route, {
            history: new can.Observe({path:getPath()}),
            _paramsMatcher: paramsMatcher,
            _querySeparator: '?',
            _setup: function() {
                // intercept routable links
                can.$('body').on('click', 'a', function(e) {
                    if(can.route.updateWith(this.pathname+this.search)) {
                        e.preventDefault();
                    }
                });
                can.route.history.bind('path',can.route.setState);
            },
            updateWith: function(href) {
                var curParams = can.route.deparam(href);

                if(curParams.route) {
                    can.route.attr(curParams, true);
                    return true;
                }
                return false;
            },
            _getHash: getPath,
            _setHash: function(serialized) {
                var path = can.route.param(serialized, true);
                if(path !== can.route._getHash()) {
                    can.route.updateLocation(path);
                }
                return path;
            },
            current: function( options ) {
                return this._getHash() === can.route.param(options);
            },
            /**
             * This is a blunt hook for updating the window.location.
             * You may prefer to use replaceState instead of pushState in some circumstances,
             * in which case you can overwrite this method and handle the change yourself.
             */
            updateLocation: function(path) {
                history.pushState(null, null, path);
            },
            url: function( options, merge ) {
                if (merge) {
                    options = can.extend({}, can.route.deparam( this._getHash()), options);
                }
                return can.route.param(options);
            }
        });
    }

	return can;
});
