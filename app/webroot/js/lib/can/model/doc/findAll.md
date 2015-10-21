@description Retrieve multiple resources from a server.
@function can.Model.findAll findAll
@parent can.Model.static

@signature `can.Model.findAll( params[, success[, error]] )`

Retrieve multiple resources from a server.

@param {Object} params Values to filter the request or results with.
@param {function(can.Model.List)} [success(list)] A callback to call on successful retrieval. The callback receives
a can.Model.List of the retrieved resources.
@param {function(can.AjaxSettings)} [error(xhr)] A callback to call when an error occurs. The callback receives the
XmlHttpRequest object.
@return {can.Deferred} A deferred that resolves to a [can.Model.List] of retrieved models.


@signature `can.Model.findAll: findAllData( params ) -> deferred`

Implements `findAll` with a [can.Model.findAllData function]. This function
is passed to [can.Model.makeFindAll makeFindAll] to create the external
`findAll` method.

```
findAll: function(params){
 return $.get("/tasks",params)
}
```

@param {can.Model.findAllData} findAllData A function that accepts parameters
specifying a list of instance data to retrieve and returns a [can.Deferred]
that resolves to an array of those instances.

@signature `can.Model.findAll: "[METHOD] /path/to/resource"`

Implements `findAll` with a HTTP method and url to retrieve instance data.

   findAll: "GET /tasks"

If `findAll` is implemented with a string, this gets converted to
a [can.Model.findAllData findAllData function]
which is passed to [can.Model.makeFindAll makeFindAll] to create the external
`findAll` method.

@param {HttpMethod} METHOD An HTTP method. Defaults to `"GET"`.

@param {STRING} url The URL of the service to retrieve JSON data.

@return {JSON} The service should return a JSON object like:

```
{
 "data": [
   { "id" : 1, "name" : "do the dishes" },
   { "id" : 2, "name" : "mow the lawn" },
   { "id" : 3, "name" : "iron my shirts" }
 ]
}
```

This object is passed to [can.Model.models] to turn it into instances.

_Note: .findAll can also accept an array, but you
probably [should not be doing that](http://haacked.com/archive/2008/11/20/anatomy-of-a-subtle-json-vulnerability.aspx)._


@signature `can.Model.findAll: {ajaxSettings}`

Implements `findAll` with a [can.AjaxSettings ajax settings object].

```
findAll: {url: "/tasks", dataType: "json"}
```

If `findAll` is implemented with an object, it gets converted to
a [can.Model.findAllData findAllData function]
which is passed to [can.Model.makeFindAll makeFindAll] to create the external
`findAll` method.

@param {can.AjaxSettings} ajaxSettings A settings object that
specifies the options available to pass to [can.ajax].

@body

## Use

`findAll( params, success(instances), error(xhr) ) -> Deferred` is used to retrieve model
instances from the server. After implementing `findAll`, use it to retrieve instances of the model
like:

```
Recipe.findAll({favorite: true}, function(recipes){
 recipes[0].attr('name') //-> "Ice Water"
}, function( xhr ){
 // called if an error
}) //-> Deferred
```

`findAll` uses [can.Model.parseModels parseModels] to parse the returned data. `can.Model.parseModels` 
can be overwritten to handle non-standard data formats.

Before you can use `findAll`, you must implement it.

## Implement with a URL

Implement findAll with a url like:

```
Recipe = can.Model.extend({
 findAll : "/recipes.json"
},{});
```

The server should return data that looks like:

```
[
 {"id" : 57, "name": "Ice Water"},
 {"id" : 58, "name": "Toast"}
]
```

## Implement with an Object

Implement findAll with an object that specifies the parameters to
`can.ajax` (jQuery.ajax) like:

```
Recipe = can.Model.extend({
 findAll : {
   url: "/recipes.xml",
   dataType: "xml"
 }
},{});
```

## Implement with a Function

To implement with a function, `findAll` is passed __params__ to filter
the instances retrieved from the server and it should return a
deferred that resolves to an array of model data. For example:

```
Recipe = can.Model.extend({
 findAll : function(params){
   return $.ajax({
     url: '/recipes.json',
     type: 'get',
     dataType: 'json'})
 }
},{});
```
