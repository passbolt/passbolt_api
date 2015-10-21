@description Specifies how to create a new resource on the server. `create(serialized)` is called
by [can.Model.prototype.save save] if the model instance [can.Model.prototype.isNew is new].
@function can.Model.create create
@parent can.Model.static


@signature `can.Model.create: function(serialized) -> deferred`

Specify a function to create persistent instances. The function will
typically perform an AJAX request to a service that results in
creating a record in a database.

@param {Object} serialized The [can.Map::serialize serialized] properties of
the model to create.
@return {can.Deferred} A Deferred that resolves to an object of attributes
that will be added to the created model instance.  The object __MUST__ contain
an [can.Model.id id] property so that future calls to [can.Model.prototype.save save]
will call [can.Model.update].


@signature `can.Model.create: "[METHOD] /path/to/resource"`

Specify a HTTP method and url to create persistent instances.

If you provide a URL, the Model will send a request to that URL using
the method specified (or POST if none is specified) when saving a
new instance on the server. (See below for more details.)

@param {HttpMethod} METHOD An HTTP method. Defaults to `"POST"`.
@param {STRING} url The URL of the service to retrieve JSON data.


@signature `can.Model.create: {ajaxSettings}`

Specify an options object that is used to make a HTTP request to create
persistent instances.

@param {can.AjaxSettings} ajaxSettings A settings object that
specifies the options available to pass to [can.ajax].

@body

`create(attributes) -> Deferred` is used by [can.Model::save save] to create a
model instance on the server.

## Implement with a URL

The easiest way to implement create is to give it the url
to post data to:

```
var Recipe = can.Model.extend({
 create: "/recipes"
},{})
```

This lets you create a recipe like:

```
new Recipe({name: "hot dog"}).save();
```


## Implement with a Function

You can also implement create by yourself. Create gets called
with `attrs`, which are the [can.Map::serialize serialized] model
attributes.  Create returns a `Deferred`
that contains the id of the new instance and any other
properties that should be set on the instance.

For example, the following code makes a request
to `POST /recipes.json {'name': 'hot+dog'}` and gets back
something that looks like:

```
{
 "id": 5,
 "createdAt": 2234234329
}
```

The code looks like:

```
can.Model.extend("Recipe", {
 create : function( attrs ){
   return $.post("/recipes.json",attrs, undefined ,"json");
 }
},{})
```
