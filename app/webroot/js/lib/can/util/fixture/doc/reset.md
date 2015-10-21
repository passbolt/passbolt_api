@description Reset the fixture store.
@function can.fixture.types.Store.reset
@parent can.fixture.types.Store
@signature `store.reset()`

@body
`store.reset()` resets the store to contain its original data. This is useful for making tests that operate independently.

## Basic Example

After creating a `taskStore` and hooking it up to a `task` model in the "Basic Example" in [can.fixture.store store's docs], a test might create several tasks like:

    new Task({name: "Take out trash", ownerId: 5}).save();

But, another test might need to operate on the original set of tasks created by `can.fixture.store`. Reset the task store with:

    taskStore.reset()

