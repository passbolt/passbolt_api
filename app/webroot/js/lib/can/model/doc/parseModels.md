@function can.Model.parseModels parseModels
@parent can.Model.static
@description Convert raw xhr data into an array or object that can be used to
create a [can.Model.List].
@release 2.1

@signature `can.Model.parseModels(data, xhr)`

@param { } data The raw data from a `[can.Model.findAll findAll()]` request.

@param {XMLHTTPRequest} [xhr] The XMLHTTPRequest object used to make the request.

@return {Array|Object} A JavaScript Object or Array that [can.Model.models]
can convert into the Model's List.

@signature `parseModels: "PROPERTY"`

Creates a `parseModels` function that looks for the array of instance data in the PROPERTY
property of the raw response data of [can.Model.findAll].

@body

## Use

`can.Model.parseModels(data, xhr)` is used to
convert the raw response of a [can.Model.findAll] request
into an object or Array that [can.Model.models] can use to create
a [can.Model.List] of model instances.

This method is never called directly. Instead the deferred returned
by findAll is piped into `parseModels` and the result of that
is sent to [can.Model.models].

If your server is returning data in non-standard way,
overwriting `can.Model.parseModels` is the best way to normalize it.

## Expected data format

By default, [can.Model.models] expects data to be an array of name-value pair
objects like:

```
[{id: 1, name : "dishes"},{id:2, name: "laundry"}, ...]
```

It can also take an object with additional data about the array like:

```
{
 count: 15000 //how many total items there might be
 data: [{id: 1, name : "justin"},{id:2, name: "brian"}, ...]
}
```

In this case, models will return a [can.Model.List] of instances found in
data, but with additional properties as expandos on the list:

```
var tasks = Task.models({
 count : 1500,
 data : [{id: 1, name: 'dishes'}, ...]
})
tasks.attr("name") // -> 'dishes'
tasks.count // -> 1500
```

If your data does not look like one of these formats, overwrite `parseModels`.

## Overwriting parseModels

If your service returns data like:

```
{ thingsToDo: [{name: "dishes", id: 5}] }
```

You will want to overwrite `parseModels` to pass the models what it expects like:

```
Task = can.Model.extend({
 parseModels: function(data){
   return data.thingsToDo;
 }
},{});
```

You could also do this like:

```
Task = can.Model.extend({
 parseModels: "thingsToDo"
},{});
```

`can.Model.models` passes each instance's data to `can.Model.model` to
create the individual instances.
