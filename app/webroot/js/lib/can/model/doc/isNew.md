@function can.Model.prototype.isNew isNew
@parent can.Model.prototype
@description Check if a Model has yet to be saved on the server.
@signature `model.isNew()`
@return {Boolean} Whether an instance has been saved on the server.
(This is determined by whether `id` has a value set yet.)

@body
`isNew()` returns if the instance is has been created
on the server. This is essentially if the [can.Model.id]
property is null or undefined.

```
new Recipe({id: 1}).isNew() //-> false
```