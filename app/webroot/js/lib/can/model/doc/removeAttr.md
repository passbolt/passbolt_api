@property {Boolean} can.Model.removeAttr removeAttr
@parent can.Model.static
Sets whether model conversion should remove non existing attributes or merge with
the existing attributes. The default is `false`.
For example, if `Task.findOne({ id: 1 })` returns

```
{ id: 1, name: 'Do dishes', index: 1, color: ['red', 'blue'] }
```

for the first request and

```
{ id: 1, name: 'Really do dishes', color: ['green'] }
```

for the next request, the actual model attributes would look like:

```
{ id: 1, name: 'Really do dishes', index: 1, color: ['green', 'blue'] }
```

Because the attributes of the original model and the updated model will
be merged. Setting `removeAttr` to `true` will result in model attributes like

```
{ id: 1, name: 'Really do dishes', color: ['green'] }
```
