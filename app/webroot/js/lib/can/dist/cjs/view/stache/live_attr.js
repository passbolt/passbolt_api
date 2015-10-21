/*!
 * CanJS - 2.2.9
 * http://canjs.com/
 * Copyright (c) 2015 Bitovi
 * Fri, 11 Sep 2015 23:12:43 GMT
 * Licensed MIT
 */

/*can@2.2.9#view/stache/live_attr*/
var can = require('../../util/util.js');
var live = require('../live/live.js');
var elements = require('../elements.js');
var viewCallbacks = require('../callbacks/callbacks.js');
live = live || can.view.live;
elements = elements || can.view.elements;
viewCallbacks = viewCallbacks || can.view.callbacks;
module.exports = {
    attributes: function (el, compute, scope, options) {
        var oldAttrs = {};
        var setAttrs = function (newVal) {
            var newAttrs = live.getAttributeParts(newVal), name;
            for (name in newAttrs) {
                var newValue = newAttrs[name], oldValue = oldAttrs[name];
                if (newValue !== oldValue) {
                    can.attr.set(el, name, newValue);
                    var callback = viewCallbacks.attr(name);
                    if (callback) {
                        callback(el, {
                            attributeName: name,
                            scope: scope,
                            options: options
                        });
                    }
                }
                delete oldAttrs[name];
            }
            for (name in oldAttrs) {
                elements.removeAttr(el, name);
            }
            oldAttrs = newAttrs;
        };
        var handler = function (ev, newVal) {
            setAttrs(newVal);
        };
        compute.bind('change', handler);
        can.bind.call(el, 'removed', function () {
            compute.unbind('change', handler);
        });
        setAttrs(compute());
    }
};
