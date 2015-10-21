@description Update a resource on the server.
@function can.Model.update update
@parent can.Model.static
@signature `can.Model.update: "[METHOD] /path/to/resource"`
If you provide a URL, the Model will send a request to that URL using
the method specified (or PUT if none is specified) when updating an
instance on the server. (See below for more details.)
@return {can.Deferred} A Deferred that resolves to the updated model.

@signature `can.Model.update: function(id, serialized) -> can.Deffered`
If you provide a function, the Model will expect you to do your own AJAX requests.
@param { } id The ID of the model to update.
@param {Object} serialized The [can.Map::serialize serialized] properties of
the model to update.
@return {can.Deferred} A Deferred that resolves to the updated model.

@body
`update( id, attrs ) -> Deferred` is used by [can.Model::save save] to
update a model instance on the server.

## Implement with a URL

The easist way to implement update is to just give it the url to `PUT` data to:

```
Recipe = can.Model.extend({
 update: "/recipes/{id}"
},{});
```

This lets you update a recipe like:

```
Recipe.findOne({id: 1}, function(recipe){
 recipe.attr('name','salad');
 recipe.save();
})
```

This will make an XHR request like:

```
PUT /recipes/1
name=salad
```

If your server doesn't use PUT, you can change it to post like:

```
Recipe = can.Model.extend({
 update: "POST /recipes/{id}"
},{});
```

The server should send back an object with any new attributes the model
should have.  For example if your server updates the "updatedAt" property, it
should send back something like:

```
// PUT /recipes/4 {name: "Food"} ->
{
 updatedAt : "10-20-2011"
}
```

## Implement with a Function

You can also implement update by yourself.  Update takes the `id` and
`attributes` of the instance to be updated.  Update must return
a [can.Deferred Deferred] that resolves to an object that contains any
properties that should be set on the instance.

For example, the following code makes a request
to '/recipes/5.json?name=hot+dog' and gets back
something that looks like:

```
{
 updatedAt: "10-20-2011"
}
```

The code looks like:

```
Recipe = can.Model.extend({
 update : function(id, attrs ) {
   return $.post("/recipes/"+id+".json",attrs, null,"json");
 }
},{});
```
