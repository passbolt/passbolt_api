@function can.Model.prototype.destroy destroy
@parent can.Model.prototype
@description Destroy a Model on the server.
@signature `model.destroy([success[, error]])`
@param {function} [success] A callback to call on successful destruction. The callback receives
the can.Model as it was just prior to destruction.
@param {function} [error] A callback to call when an error occurs. The callback receives the
XmlHttpRequest object.
@return {can.Deferred} A Deferred that resolves to the Model as it was before destruction.

@body
Destroys the instance by calling
[can.Model.destroy] with the id of the instance.

```
recipe.destroy(success, error);
```

This triggers "destroyed" events on the instance and the
Model constructor function which can be listened to with
[can.Model::bind] and [can.Model.bind].

```
Recipe = can.Model.extend({
 destroy : "DELETE /services/recipes/{id}",
 findOne : "/services/recipes/{id}"
},{})

Recipe.bind("destroyed", function(){
 console.log("a recipe destroyed");
});

// get a recipe
Recipe.findOne({id: 5}, function(recipe){
 recipe.bind("destroyed", function(){
   console.log("this recipe destroyed")
 })
 recipe.destroy();
})
```
