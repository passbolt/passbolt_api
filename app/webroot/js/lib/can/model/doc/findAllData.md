@typedef {function} can.Model.findAllData findAllData

Retrieves a list of items for [can.Model.models], typically by making an 
Ajax request.

@param {Object} params Specifies the list to be retrieved.

@return {can.Deferred} A deferred that resolves to a data structure
that can be understood by [can.Model.models].




@body

## Use

Typically, `findAll` is implemented with a "string" or [can.AjaxSettings ajax settings object] like:

```
findAll: "GET /tasks"
```
    
or

```
findAll: {url: "/tasks", dataType: "custom"}
```

[can.Model.setup] converts
