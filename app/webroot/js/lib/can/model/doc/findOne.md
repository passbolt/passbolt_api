
@description Retrieve a resource from a server.
@function can.Model.findOne findOne
@parent can.Model.static

@signature `can.Model.findOne( params[, success[, error]] )`

Retrieve a single instance from the server.

@param {Object} params Values to filter the request or results with.
@param {function(can.Model)} [success(model)] A callback to call on successful retrieval. The callback receives
the retrieved resource as a can.Model.
@param {function(can.AjaxSettings)} [error(xhr)] A callback to call when an error occurs. The callback receives the
XmlHttpRequest object.
@return {can.Deferred} A deferred that resolves to a [can.Model] instance of the retrieved model

@signature `can.Model.findOne: findOneData( params ) -> deferred`

Implements `findOne` with a [can.Model.findOneData function]. This function
is passed to [can.Model.makeFindOne makeFindOne] to create the external
`findOne` method.

```
findOne: function(params){
 return $.get("/task/"+params.id)
}
```

@param {can.Model.findOneData} findOneData A function that accepts parameters
specifying an instance to retreive and returns a [can.Deferred]
that resolves to that instance.

@signature `can.Model.findOne: "[METHOD] /path/to/resource"`

Implements `findOne` with a HTTP method and url to retrieve an instance's data.

```
findOne: "GET /tasks/{id}"
```

If `findOne` is implemented with a string, this gets converted to
a [can.Model.makeFindOne makeFindOne function]
which is passed to [can.Model.makeFindOne makeFindOne] to create the external
`findOne` method.

@param {HttpMethod} METHOD An HTTP method. Defaults to `"GET"`.

@param {STRING} url The URL of the service to retrieve JSON data.

@signature `can.Model.findOne: {ajaxSettings}`

Implements `findOne` with a [can.AjaxSettings ajax settings object].

   findOne: {url: "/tasks/{id}", dataType: "json"}

If `findOne` is implemented with an object, it gets converted to
a [can.Model.makeFindOne makeFindOne function]
which is passed to [can.Model.makeFindOne makeFindOne] to create the external
`findOne` method.

@param {can.AjaxSettings} ajaxSettings A settings object that
specifies the options available to pass to [can.ajax].

@body

## Use

`findOne( params, success(instance), error(xhr) ) -> Deferred` is used to retrieve a model
instance from the server.

Use `findOne` like:

```
Recipe.findOne({id: 57}, function(recipe){
recipe.attr('name') //-> "Ice Water"
}, function( xhr ){
// called if an error
}) //-> Deferred
```

Before you can use `findOne`, you must implement it.

## Implement with a URL

Implement findAll with a url like:

```
Recipe = can.Model.extend({
 findOne : "/recipes/{id}.json"
},{});
```


If `findOne` is called like:

```
Recipe.findOne({id: 57});
```

The server should return data that looks like:

```
{"id" : 57, "name": "Ice Water"}
```

## Implement with an Object

Implement `findOne` with an object that specifies the parameters to
`can.ajax` (jQuery.ajax) like:

```
Recipe = can.Model.extend({
 findOne : {
   url: "/recipes/{id}.xml",
   dataType: "xml"
 }
},{})
```

## Implement with a Function

To implement with a function, `findOne` is passed __params__ to specify
the instance retrieved from the server and it should return a
deferred that resolves to the model data.  Also notice that you now need to
build the URL manually. For example:

```
Recipe = can.Model.extend({
 findOne : function(params){
   return $.ajax({
     url: '/recipes/' + params.id,
     type: 'get',
     dataType: 'json'})
 }
},{})
```
