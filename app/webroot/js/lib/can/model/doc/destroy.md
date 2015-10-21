@description Destroy a resource on the server.
@function can.Model.destroy destroy
@parent can.Model.static

@signature `can.Model.destroy: function(id) -> deferred`

If you provide a function, the Model will expect you to do your own AJAX requests.
@param { } id The ID of the resource to destroy.
@return {can.Deferred} A Deferred that resolves to the destroyed model.

@signature `can.Model.destroy: "[METHOD] /path/to/resource"`

If you provide a URL, the Model will send a request to that URL using
the method specified (or DELETE if none is specified) when deleting an
instance on the server. (See below for more details.)

@return {can.Deferred} A Deferred that resolves to the destroyed model.

@body
`destroy(id) -> Deferred` is used by [can.Model::destroy] remove a model
instance from the server.

## Implement with a URL

You can implement destroy with a string like:

```
Recipe = can.Model.extend({
 destroy : "/recipe/{id}"
},{})
```

And use [can.Model::destroy] to destroy it like:

```
Recipe.findOne({id: 1}, function(recipe){
    recipe.destroy();
});
```

This sends a `DELETE` request to `/thing/destroy/1`.

If your server does not support `DELETE` you can override it like:

```
Recipe = can.Model.extend({
 destroy : "POST /recipe/destroy/{id}"
},{})
```

## Implement with a function

Implement destroy with a function like:

```
Recipe = can.Model.extend({
 destroy : function(id){
   return $.post("/recipe/destroy/"+id,{});
 }
},{})
```

Destroy just needs to return a deferred that resolves.