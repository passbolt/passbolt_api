@typedef {function(can.AjaxSettings,can.fixture.types.responseHandler)} can.fixture.types.requestHandler(request,response) requestHandler
@parent can.fixture.types

@description Specifies the response of a fixture. Used in [can.fixture].

@param {can.AjaxSettings} request The ajax settings object that
was passed to [can.ajax] or a jQuery ajax method.  Any templated
portions of the url passed to [can.fixture] are added as
data to `request.data`.  For example, calling:

    $.get("/todos/5");
    
With the following fixture:

    can.fixture("/todos/:id", function(request, response){ })

`request.data.id` will be `5`.

@param {can.fixture.types.responseHandler} [response]

Optionally called to specify the response of the fixture.

@param {Object.<String,String>} requestHeaders

A map of request headers specified by [can.ajax]'s headers property.

@return {*|undefined} If a value is returned, it is used as a JSON
response body. If nothing is returned, it's expected that `responseHandler`
was used.

@body

## Use

`requestHandler` functions are passed to [can.fixture] like:

    can.fixture("GET /something", function(request, response, headers){ })

These functions are called with:

  - `request` - The options provided to the ajax method, unmodified,
    and thus, without defaults from ajaxSettings.
  - `response` - The response callback.
  - `headers` - A map of key/value request headers.
  
Specify the result of the Ajax request by either returning the result 
within the `requestHandler` or calling [can.fixture.types.responseHandler response].


### Returning the result directly

Return the data result of an Ajax request directly within
a `requestHandler`.  For example:

    can.fixture("GET /something", function() {
      return { foo: "bar" };
    })

    // example code that uses the previous fixture
    $.get("GET /something",function(data){
      data.foo //-> "bar"
    })

### Calling response to specify the result

The [can.fixture.types.responseHandler response] callback passed to a `requestHandler`
can be used to specify all values in a response. For example:

    can.fixture("GET /something", function( request, response ) {
      response(
        200,
        "success",
        { json: {foo: "bar"} },
        {
          location: "foo/bar"
        })
    })

    // example code that uses the previous fixture
    $.get("GET /something",function(data, textStatus, jqXHR){
      data.foo   //-> "bar"
      textStatus //-> "success"
      jqXHR.getResponseHeader("location") //-> "foo/bar"
    })

The [can.fixture.types.responseHandler response] callback can also be called with just 
a single JSON object for the common case where you want a successful response with JSON data:

    can.fixture("/foobar.json", function(request, response){
      response({ foo: "bar" });
    })

If you want to return an array of data respond like this:

    can.fixture("/tasks.json",
      function(request, response){
        response([ "first", "second", "third"]);
      })

