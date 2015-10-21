@function can.fixture can.fixture
@parent canjs
@test can/util/fixture/test.html
@group can.fixture.types Types

@description Simulate AJAX requests.

> Note: can.fixture depends on the can.object plugin. If you are not using the AMD or Steal version of CanJS you need to include [can.object.js](http://canjs.com/release/latest/can.object.js) __before__ can.fixture.

In the following CanJS community hangout we also talk about CanJS models and fixtures:

<iframe width="662" height="372" src="https://www.youtube.com/watch?v=Tyr_087p8CQ" frameborder="0" allowfullscreen></iframe>

@signature `can.fixture( url, toUrl )`

Trap requests from one url and redirect them from another.

@param {String} url

Trap requests made by [can.ajax] to this url.

@param {String|null} toUrl

Redirect requests to this url. If `null` is provided,
this removes a the fixture at `url`.

@signature `can.fixture( url, handler(request, response, requestHeaders) )`

Trap requests to a url and provide the response with a
callback function.

@param {String} url Trap requests made 
by [can.ajax] to this url. 

The url can be templated with tags that
look like `{TEMPLATE}`. For example: "/users/{id}". Any templated
values get added to the `handler`'s request object's data.

@param {can.fixture.types.requestHandler} handler

Specifies the response of the fixture. `handler` gets called with
the [can.ajax] [can.AjaxSettings settings object] and a [can.fixture.types.responseHandler response handler]
that is used to specify the response.

@signature `can.fixture(fixtures)`

Configures multiple ajax traps.

@param {Object.<url,can.fixture.types.requestHandler|String>} fixtures

An mapping of templated urls to redirect urls
or [can.fixture.types.requestHandler request handler functions].

    can.fixture({
      "/tasks": "/fixtures/tasks.json",
      "DELETE /tasks/{id}": function(){
      	return {};
      }
    })


@body

## Use


`can.fixture` intercepts an AJAX request and simulates
the response with a file or function. Use them to develop 
JavaScript independently of the backend services.

The following simulates a `GET` request to `/recipes`:

    can.fixture("GET /recipes",function(){
      return [
        {id: 1, name: "omelette"},
        {id: 2, name: "hot dog"}
      ];
    });

Requests made to `GET /recipes` with [can.ajax], [jQuery.ajax](http://api.jquery.com/jQuery.ajax/),or
[jQuery.get](http://api.jquery.com/jQuery.get/) will receive the data
returned by the fixture function above:

    $.get("/recipes",function(data){
      assertEqual( data.length, 2 )
    })

There are two common ways of using fixtures.  The first is to
map Ajax requests to another file.  The following
intercepts requests to `/tasks.json` and directs them
to `fixtures/tasks.json`:
    
    can.fixture("/tasks.json", "fixtures/tasks.json");

The other common option is to generate the Ajax response with
a [can.fixture.types.requestHandler] function.  The following intercepts updating tasks at
`/tasks/ID.json` and responds with updated data:

    can.fixture("GET /tasks/{id}",function(request,response){
      return {id: request.data.id, name: "fix tires."}
    })

A [can.fixture.types.requestHandler requestHandler] function's [can.fixture.types.responseHandler response]
argument can be used to specify even more details of the Ajax response:

    can.fixture("GET /tasks/{id}",function(request,response){
      response(
        200,
        "success",
        {id: request.data.id, name: "fix tires."},
        {location: "/tasks/"+request.data.id})
    })

Read more about [can.fixture.types.requestHandler requestHandler] and its
[can.fixture.types.responseHandler response] argument on their own documentation pages.

## Templated Urls

Often, you want a dynamic fixture to handle urls for multiple resources (for example a REST url scheme).
can.fixture's templated urls allow you to match urls with a wildcard.

The following example simulates services that get and update 100 todos.

    // create todos
    var todos = {};
    for(var i = 0; i < 100; i++) {
      todos[i] = {
        id: i,
        name: "Todo "+i
      }
    }
    can.fixture("GET /todos/{id}",
      function(request, response, headers){
        // return the JSON data
        // notice that id is pulled from the url and added to data
        response(todos[request.data.id]);
      })

    can.fixture("PUT /todos/{id}",
      function(request, response, headers){
        // update the todo's data
        can.extend(todos[request.data.id], request.data );
        response({});
      })

Notice that data found in templated urls (ex: `{id}`) is added to the request's data object.

## Simulating Errors

The following simulates an unauthorized request
to `/foo`.

    can.fixture("/foo",
      function(request, response) {
        response(401,"{type: 'unauthorized'}");
      });

This could be received by the following Ajax request:

    can.ajax({
      url: '/foo',
      error : function(jqXhr, status, statusText){
        // status === 'error'
        // statusText === "{type: 'unauthorized'}"
      }
    })

## Turning off Fixtures

You can remove a fixture by passing `null` for the fixture option:

    // add a fixture
    can.fixture("GET todos.json","//fixtures/todos.json");

    // remove the fixture
    can.fixture("GET todos.json", null)

You can also set [can.fixture.on] to false:

    can.fixture.on = false;

## Bypassing Fixtures

While there are few cases where you would need to, it is possible to bypass a
fixture completely, without turning off fixtures globally. This is done by passing
`fixture: false` to your AJAX settings. This will prevent `can.fixture` from
trapping your request, and actually send it to the server.

    // add a fixture
    can.fixture('POST /foo', '//fixtures/foo.json');

    // Send AJAX call to server, even if fixtures are on
    can.ajax({
        type: 'POST',
        url: '/foo',
        fixture: false
    });

## can.fixture.store

[can.fixture.store] makes a CRUD service layer that handles sorting, grouping, filtering and more. Use
it with a [can.Model] like this:

    var Todo = can.Model.extend({
      findAll : 'GET /todos',
      findOne : 'GET /todos/{id}',
      create  : 'POST /todos',
      update  : 'PUT /todos/{id}',
      destroy : 'DELETE /todos/{id}'
      }, {});

    var store = can.fixture.store(100, function(i) {
      return {
        id : i,
        name : 'Todo ' + i
      }
    });

    can.fixture('GET /todos', store.findAll);
    can.fixture('GET /todos/{id}', store.findOne);
    can.fixture('POST /todos', store.create);
    can.fixture('PUT /todos/{id}', store.update);
    can.fixture('DELETE /todos/{id}', store.destroy);

## Testing Performance

Dynamic fixtures are awesome for performance testing.  Want to see what
10000 files does to your app's performance?  Make a fixture that returns 10000 items.

What to see what the app feels like when a request takes 5 seconds to return?  Set
[can.fixture.delay] to 5000.


Since `response` is called asynchronously you can also set a custom fixture timeout like this:

    can.fixture( "/foobar.json", function(request, response){
      setTimeout(function() {
        response({ foo: "bar" });
      }, 1000);
    })

## Organizing fixtures

The __best__ way of organizing fixtures is to have a 'fixtures.js' file that steals
<code>can/util/fixture</code> and defines all your fixtures.  For example,
if you have a 'todo' application, you might
have <code>todo/fixtures/fixtures.js</code> look like:

    steal({
            path: '//can/util/fixture.js',
            ignore: true
          })
          .then(function(){

      can.fixture({
          type: 'get',
          url: '/services/todos.json'
        },
        '//todo/fixtures/todos.json');

      can.fixture({
          type: 'post',
          url: '/services/todos.json'
        },
        function(request, response, settings){
            response({
                id: Math.random(),
                name: settings.data.name
            })
        });

    })

__Notice__: We used steal's ignore option to prevent
loading the fixture plugin in production.

Finally, we steal <code>todo/fixtures/fixtures.js</code> in the
app file (<code>todo/todo.js</code>) like:


    steal({path: '//todo/fixtures/fixtures.js',ignore: true});

    //start of your app's steals
    steal( ... )

We typically keep it a one liner so it's easy to comment out.

### Switching Between Sets of Fixtures

If you are using fixtures for testing, you often want to use different
sets of fixtures.  You can add something like the following to your fixtures.js file:

    if( /fixtureSet1/.test( window.location.search) ){
      can.fixture("/foo","//foo/fixtures/foo1.json');
    } else if(/fixtureSet2/.test( window.location.search)){
      can.fixture("/foo","//foo/fixtures/foo2.json');
    } else {
      // default fixtures (maybe no fixtures)
    }





