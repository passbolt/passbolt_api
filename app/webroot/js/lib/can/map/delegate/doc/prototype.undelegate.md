@function can.Map.prototype.undelegate undelegate
@parent can.Map.delegate 1
@plugin can/map/delegate

@signature `observe.undelegate( selector, event, handler )`
`undelegate( selector, event, handler )` removes a delegated event handler from an observe.

```
observe.undelegate('name', 'set', handler);
```

@param {String} selector the attribute name of the object you want to undelegate from.
@param {String} event the event name
@param {Function} handler the callback handler
@return {can.Map} the observe for chaining