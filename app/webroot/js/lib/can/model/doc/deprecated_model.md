@function can.Model.model model
@parent can.Model.static

@deprecated {2.1} Prior to 2.1, `.model` was used to convert ajax
responses into a data format useful for converting them into a can.Model instance
AND for converting them into that instance. In 2.1, [can.Model.parseModel] should
be used to convert the ajax response into a data format useful to [can.Model.model].

@description Convert raw data into a can.Model instance. If data's [can.Model.id id]
matches a item in the store's `id`, `data` is merged with the instance and the
instance is returned.


@signature `can.Model.model(data)`
@param {Object} data The data to convert to a can.Model instance.
@return {can.Model} An instance of can.Model made with the given data.


@body

## Use

`.models(data)` is used to create or retrieve a [can.Model] instance
with the data provided. If data matches an instance in the [can.Model.store],
that instance will be merged with the item's data and returneds

For example

```
Task = can.Model.extend({},{})

var t1 = new Task({id: 1, name: "dishes"})

// Binding on a model puts it in the store
t1.bind("change", function(){})

var task = Task.model({id: 1, name : "dishes", complete : false})

t1 === task //-> true
t1.attr("complete")  //-> false
```
