@function can.Model.makeFindOne
@parent can.Model.static

`makeFindOne` is a hook that lets you define special `findOne` behavior. 
It is a generator function that lets you return the function that will 
actually be called when `findOne` is called.

@signature `can.Model.makeFindOne: function(findOneData) -> findOne`

Returns the external `findOne` method given the implemented [can.Model.findOneData findOneData] function.

@param {can.Model.findOneData} findOneData

[can.Model.findOne] is implemented with a `String`, [can.ajax ajax settings object], or
[can.Model.findOneData findOneData] function. If it is implemented as
a `String` or [can.ajax ajax settings object], those values are used
to create a [can.Model.findOneData findOneData] function.

The [can.Model.findOneData findOneData] function is passed to `makeFindOne`. `makeFindOne`
should use `findOneData` internally to get the raw data for the request.

@return {function(params,success,error):can.Deferred}

Returns function that implements the external API of `findOne`.

@body

## Use

When a user calls `MyModel.findOne({})`, the function returned by 
`makeFindOne` will be called. Here you can specify what you want to happen 
before the real request for data is made. Call the function passed in `findOneData` with `params` to make the AJAX request, or whatever the external request for data normally does.

`makeFindOne` can be used to implement base models that perform special
behavior, like caching, or adding special parameters to the request object. `makeFindOne` is passed a [can.Model.findOneData findOneData] function that retrieves raw
data. It should return a function that when called, uses
the findOneData function to get the raw data and convert it to a model instance with
[can.Model.model model].

## Caching

The following uses `makeFindOne` to create a base `CachedModel`:

```js
CachedModel = can.Model.extend({
 makeFindOne: function(findOneData){
   // A place to store requests
   var cachedRequests = {};

   return function(params, success, error){
     // is this not cached?
     if(! cachedRequests[JSON.stringify(params)] ) {
       var self = this;
       // make the request for data, save deferred
       cachedRequests[JSON.stringify(params)] =
         findOneData(params).then(function(data){
           // convert the raw data into instances
           return self.model(data)
         })
     }
     // get the saved request
     var def = cachedRequests[JSON.stringify(params)]
     // hookup success and error
     def.then(success,error)
     return def;
   }
 }
},{})
```

The following Todo model will never request the same todo twice:

```js
Todo = CachedModel({
 findOne: "/todos/{id}"
},{})

// widget 1
Todo.findOne({id: 5})

// widget 2
Todo.findOne({id: 5})
```
