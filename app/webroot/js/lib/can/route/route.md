@function can.route can.route
@group can.route.static static
@inherits can.Map
@download can/route
@test can/route/test.html
@parent canjs
@group can.route.plugins plugins
@link ../docco/route/route.html docco

@description Manage browser history and
client state by synchronizing the window.location.hash with
a [can.Map].

@signature `can.route( template [, defaults] )`

Create a route matching rule.

@param {String} template the fragment identifier to match.  The fragment identifier
should start with either a character (a-Z) or colon (:).  Examples:

    can.route(":foo")
    can.route("foo/:bar")

@param {Object} [defaults] an object of default values
@return {can.route}

@body

## Use

Watch this video for an overview of can.route's functionality and an example showing how to connect two tab widgets to the browser's history:

<iframe width="662" height="372" src="https://www.youtube.com/embed/ef0LKDiaPZ0" frameborder="0" allowfullscreen></iframe>

In the following CanJS community we also talk about web application routing:

<iframe width="662" height="372" src="https://www.youtube.com/embed/0Hhuv5Qru9k" frameborder="0" allowfullscreen></iframe>

## Background Information

To support the browser's back button and bookmarking
in an Ajax application, most applications use
the <code>window.location.hash</code>.  By
changing the hash (via a link or JavaScript), 
one is able to add to the browser's history 
without changing the page.

This provides the basics needed to
create history enabled Ajax websites.  However,
`can.route` addresses several other needs such as:

  - Pretty urls (actually hashes)
  - Keeping routes independent of application code
  - Listening to specific parts of the history changing
  - Setup / Teardown of widgets.

## How it works

<code>can.route</code> is a [can.Map] that represents the
<code>window.location.hash</code> as an 
object.  For example, if the hash looks like:

    #!type=videos&id=5
    
the data in <code>can.route</code> looks like:

    { type: 'videos', id: 5 }


`can.route` keeps the state of the hash in-sync with the `data` contained within 
`can.route`.

## can.Map

`can.route` is a [can.Map]. Understanding
`can.Map` is essential for using `can.route` correctly.

You can listen to changes in an Observe with `bind(eventName, handler(ev, args...))` and
change can.route's properties with 
[can.Map.prototype.attr attr].

### Listening to changes in can.route

Listen to changes in history 
by [can.Map.prototype.bind bind]ing to
changes in <code>can.route</code> like:

    can.route.bind('change', function(ev, attr, how, newVal, oldVal) {
    
    })

 - `attr` - the name of the changed attribute
 - `how` - the type of Observe change event (add, set or remove)
 - `newVal`/`oldVal` - the new and old values of the attribute

### Updating can.route

Create changes in the route data with [can.Map.prototype.attr attr] like:

    can.route.attr('type','images');

Or change multiple properties at once like:

    can.route.attr({type: 'pages', id: 5}, true)

When you make changes to can.route, they will automatically
change the <code>hash</code>.

## Creating a Route

Use <code>can.route(url, defaults)</code> to create a 
route. A route is a mapping from a url to 
an object (that is the can.route's state). 
In order to map to a specific properties in the url,
prepend a colon to the name of the property like:

    can.route( "#!content/:type" )


If no routes are added, or no route is matched, 
can.route's data is updated with the [can.deparam deparamed]
hash.

    location.hash = "#!type=videos";
    // can.route -> {type : "videos"}
    
Once routes are added and the hash changes,
can.route looks for matching routes and uses them
to update can.route's data.

    can.route( "#!content/:type" );
    location.hash = "#!content/images";
    // can.route -> {type : "images"}
    can.route.attr( "type", "songs" )
    // location.hash -> "#!content/songs"
    
Default values can also be added:

    can.route("content/:type",{type: "videos" });
    location.hash = "#!content/"
    // can.route -> {type : "videos"}
    
## Initializing can.route

After your application has created all of its routes, call [can.route.ready]
to set can.route's data to match the current hash:

     can.route.ready()

## Changing the route.

Typically, you don't set <code>location.hash</code>
directly.  Instead, you can change properties on <code>can.route</code>
like:

    can.route.attr('type', 'videos')
    
This will automatically look up the appropriate 
route and update the hash.

Often, you want to create links.  <code>can.route</code> provides
the [can.route.link] and [can.route.url] helpers to make this 
easy:

    can.route.link("Videos", {type: 'videos'})

## Demo

The following demo shows the relationship between `window.location.hash`,
routes given to `can.data`,
`can.route`'s data, and events on `can.data`.  Most properties 
are editable so experiment!

@iframe can/route/demo.html 980

## IE Compatibility

Internet Explorer 6 and 7 does not support `window.onhashchange`. 
Even Internet Explorer 8 running in IE7 compatibility mode reports `true` 
for `onhashchange` in window, even though the event isn't supported.

If you are using jQuery, you can include Ben Alman's [HashChange Plugin](http://benalman.com/projects/jquery-hashchange-plugin/)
to support the event in the unsupported browser(s).

## Using routes with `can.Control`

Using templated event handlers, it is possible to listen to changes to
`can.route` within `can.Control`. This is convenient as it allows the
control to listen to and make changes whenever the route is modified, 
even outside of the control itself.

    // create the route
    can.route("#!content/:type")

    can.Control({
        // the route has changed
        "{can.route} change": function(ev, attr, how, newVal, oldVal) {
            if (attr === "type") {
                // the route has a type
            }
        }
    });

### Creating and binding routes with `can.Control.route`

Using [can.Control.route], a builtin plugin to CanJS, cuts down on the amount
of code needed to work with `can.route` in `can.Control`. With this plugin, it is possible
to both create routes and bind to `can.route` at the same time. Instead of creating
several routes to handle changes to __type__ and __id__, write something like this
in a control:

    can.Control({
        // the route is empty
        "route": function(data) {

        },
        // the route has a type
        ":type route": function(data) {

        }, 
        // the route has a type and id
        ":type/:id route": function(data) {
    
        }
    });


### Getting more specific with the `can.Map.delegate` plugin

Sometimes, you might only want to trigger a function when the route changes
only once, even if the route change gets called multiple times. By using the 
[can.Map.delegate] plugin, this is extremely easy. This plugin allows you to 
listen to change, set, add, and remove on `can.route`.

If you wanted to, say, show a list of recipes when  __type__ was set to recipe
and show a specific recipe when __id__ was set, you could do something like:

    can.Control({
        "{can.route} type=recipe set": 
                function( ev, prop, how, newVal, oldVal ) {
            // show list of recipes
        },
        "recipe/:id": function(data) {
            // show a single recipe
        }
    });

If we didn't only listen to when recipe is set, then every time we chose to
show a single recipe, we would create and show the list of recipes again which 
would not be very efficient.
