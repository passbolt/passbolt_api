@property {Object} can.Model.store store
@parent can.Model.static

@description A non-leaking global store of can.Model instances.

@body

## Use

The model store is used to store instances of a model. It serves two purposes:

1. Preventing duplicate instances of a model from creating duplicate instance copies that get out of sync.
1. Cleaning up old unused instances so that the size of this store remains minimal, and applications don't slowly collect memory over time without releasing it.

From a development perspective, the store can be used as a global hash to look up model instances. Instances are stored by their id. A model's store property thus looks like this:

    {
      "34535": {id: "34535", name: "Bob", _bindings: 3},
      "34536": {id: "34536", name: "Mike", _bindings: 3},
    }

The store is typically not used in application code, but rather is an internal feature of can.Model. However, it is possible to use it for looking up model instances or overriding the default behavior of the store to do something special.

### Duplicate Instances

The main reason to have a global store is to prevent duplicate instances from being created.

#### The problem

For example, an application could retrieve and display two lists of todos in the same page. First a list of todos due tomorrow:

    Todo.findAll({due: "tomorrow"})

That response might look like:

    [
      {id: 2, name: "finish writing docs", urgency: "low", due: "tomorrow", completed: false},
      {id: 7, name: "sell my car", urgency: "high", due: "tomorrow", completed: false}
    ]

Next a list of todos that are urgent:

```
Todo.findAll({priority: "high"})
```

That response might look like:

    [
      {id: 5, name: "take dog to the vet", urgency: "high", due: "next week", completed: false},
      {id: 7, name: "sell my car", urgency: "high", due: "tomorrow", completed: false}
    ]

As you can see, there is one todo that appears in both lists - sell my car. Without a can.Model.store, these would both be treated as independent model instances. 

If these todos are displayed in separate lists in the page, and a user marks "sell my car" as completed in the "due tomorrow" list, which causes the completed property to toggle to true, the other todo would not reflect this change. This is a big problem!

#### The solution

In can.Model, whenever the first model response is received, each item in the response will be added to the store, keyed off the [can.Model.id] property. When the second model response is received, instead of creating a second instance, it will check the store for the given id (7) and find the pre-existing model instance. A new instance won't be created. Instead, the same instance will be reused in the second response.

Therefore, when the user marks the completed property as true in the "due tomorrow" list, the other list, which is displaying the same todo instance, will reflect this change. Both lists are showing the same instance, and via live binding, will reflect the changed property visually in the DOM. Magic!

#### Future updates

Similarly, if another duplicate instance is retrieved later via another findAll, and it has a new `due` property, the store instance will be updated with that new property, and all displayed instances will update themselves. There will only be one instance with each unique ID in the page at any time.

### Non-leaking Store

The problem with a global store is that it grows in size over the lifecycle of a heavily used application without ever releasing its memory. can.Model.store solves this problem by removing any instances that are not being used anymore.

#### How it works

Each can.Model instance has a `_bindings` property, which is a reference counter keeping track of how many times this instance has been bound to. There are two ways _bindings gets incremented:

1. If the instance is bound to directly: 

    todo.bind('name', function(){})

2. If properties of the instance are bound to via live binding in a template:

    Name: {{name}}

The reverse is also true. _bindings gets decremented whenever `unbind` is called manually, or, more commonly, whenever a part of the DOM connected to live bindings gets removed. Removing live-bound portions of the DOM cause the live bindings to be removed via calls to `unbind`, which decrements the _bindings count.

What this means is whenever a model is being "shown" in the page, it has _bindings > 0. Whenever that model is no longer being "shown", its _bindings get decremented down to 0. There is an internal _cleanup that will periodically delete any instances that have _bindings === 0, allowing browser garbage collection to free up that memory.

The result is that in long running applications that stream large amounts of data, it is safe to assume that this store will not cause memory to increase over time.