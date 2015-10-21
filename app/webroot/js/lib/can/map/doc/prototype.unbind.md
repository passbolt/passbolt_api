@function can.Map.prototype.unbind unbind
@parent can.Map.prototype 8

@description Unbind event handlers from a Map.

@signature `map.unbind(eventType[, handler])`
@param {String} eventType the type of event to unbind, exactly as passed to `bind`
@param {Function} [handler] the handler to unbind

@body
`unbind` unbinds event handlers previously bound with [can.Map.prototype.bind bind].
If no _handler_ is passed, all handlers for the given event type will be unbound.


    var i = 0,
        increaseBy2 = function() { i += 2; },
        increaseBy3 = function() { i += 3; },
        o = new can.Map();

    o.bind('change', increaseBy2);
    o.bind('change', increaseBy3);
    o.attr('a', 'Alice');
    i; // 5

    o.unbind('change', increaseBy2);
    o.attr('b', 'Bob');
    i; // 8

    o.unbind('change');
    o.attr('e', 'Eve');
    i; // 8
