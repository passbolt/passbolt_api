@property {Object} can.route.pushstate
@download can/route/pushstate
@test can/route/pushstate/test.html
@parent can.route.plugins
@link ../docco/route/pushstate/pushstate.html docco

@description Changes [can.route] to use
[pushstate](https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history)
to change the window's [pathname](https://developer.mozilla.org/en-US/docs/Web/API/URLUtils.pathname) instead
of the [hash](https://developer.mozilla.org/en-US/docs/Web/API/URLUtils.hash).


@option {Object} The pushstate object comprises several properties that configure the behavior of [can.route] to work with `history.pushstate`.

@body

## Use

The pushstate plugin uses the same API as [can.route]. To start using pushstate plugin all you need is to load `can/route/pushstate`, it will set itself as default binding on [can.route].

You can check current binding by inspecting `can.route.currentBinding`, the default value is `"hashchange"`.

### Creating and changing routes

To [create](can.route.html#section_CreatingaRoute) route use `can.route(url, defaults)` like:

    can.route(":page", {page: 'homepage'});
    can.route("contacts/:username");
	can.route("books/:genre/:author");
	can.route.ready(); // do not forget to initialize can.route

Do not forget to [initialize](can.route.ready.html) `can.route` after creating all routes, do it by calling `can.route.ready()`.

List of defined routes is contained in `can.route.routes`, you can examine current `can.route` state by calling:

    can.route.attr(); //-> {page: "homepage", route: ":page"}

After creating routes and initializing `can.route` you can update current route by calling `can.route.attr(attr, newVal)`

    can.route.attr('page', 'about');
	can.route.attr(); //-> {page: "about", route: ":page"}

	// without cleaning current can.route state
	can.route.attr('username', 'veljko');
	can.route.attr(); //-> {page: "about", route: ":page", username: 'veljko'}

	// with cleaning current can.route state
	can.route.attr({username: 'veljko'}, true);
    can.route.attr(); //-> {username: "veljko", route: "contacts/:username"}

To update multiple attributes at once pass hash of attributes to `can.route.attr(hashOfAttrs, true)`. Pass `true` as second argument to clean up current state.

    can.route.attr({genre: 'sf', author: 'adams'}, true);
	can.route.attr(); //-> {genre: "sf", author: "adams", route: "books/:genre/:author"}

`window.location` acts as expected:

	window.location.pathname; //-> "/books/sf/adams"
	window.location.hash; //-> "", hash remains unchanged

To generate urls use `can.route.url({attrs})`:

    can.route.url({username: 'justinbmeyer'}) //-> '/contacts/justinbmeyer'


### Listening changes on matched route

As `can.route` is basically a [can.Map] that represents `window.location.pathname`, you can bind on it in the same way you would on any can.Map object.

To listen on any changes on `can.route` use `can.route.bind('change', callback)`, the following params will be passed to callback function:

	can.route.bind('change', function(ev, attr, how, newVal, oldVal) {
	  //-> ev:     {EventObject}
	  //-> attr:   'username'
	  //-> how:    'change'
	  //-> newVal: 'veljko'
	  //-> oldVal: undefined
	});
	can.route.attr({username: 'veljko'}, true);

You can also bind to specific attribute on can.route:

	can.route.bind('username', function(ev, newVal, oldVal) {
	  //-> ev:     {EventObject}
	  //-> newVal: 'nikica'
	  //-> oldVal: 'veljko'
	});
	can.route.attr({username: nikica}, true);


### Using different pathname root

Pushstate plugin has one additional property, `can.route.bindings.pushstate.root`, which specifies the part of that pathname that should not change. For example, if we only want to have pathnames within `http://example.com/contacts/`, we can specify a root like:

    can.route.bindings.pushstate.root = "/contacts/"
    can.route(":page");
    can.route.url({page: "list"}) //-> "/contacts/list"
    can.route.url({foo: "bar"})   //-> "/contacts/?foo=bar"

Now, all routes will start with `"/contacts/"`, the default `can.route.bindings.pushstate.root` value is `"/"`.

### Using `can.route.pushstate.js` with the `can.Control.route` plugin

The `can.Control.route` plugin is a great way to simplify your code.  Not only will it bind the event listeners for routes, but it also automagically prevents clicks on matching links from reloading the entire page.

Here are some examples of binding to pushstate-style routes in a can.Control using the `can.Control.route` plugin.  The first comment in each example is an href for a link that would trigger the route. The second is the contents of can.route.attr().  In each example, the contents of the data variable in the function will be the same as can.route.attr(), but without the route attribute.  All of these examples assume that pushstate.root is set to the default of '/':

    // Listen to the root route.  See above section on pushstate.root
    "route": function(data) {
			// <a href="/">Go Home!</a>
			// {route:""}
    },

    // A route with a trailing slash
    "/files/ route": function(data) {
			// <a href="/files/">Pretend this is a folder full of files.</a>
			// {route:"files/"}
    },

    // A route with a file extension
    "/files/batman.pdf route": function(data) {
			// <a href="/files/batman.pdf">I'm Batman.</a>
			// {route:"files/batman.pdf"}
    },

    // A route without a trailing slash
    "/files route": function(data) {
			// <a href="/files">The files are in the computer?</a>
			// {route:"files"}
    },

    // A route with a wildcard parameter
    "/files/:file_name route": function(data) {
			// <a href="/files/robin.pdf">Holy roasted metal!</a>
			// {route:"files/robin.pdf", file_name:"robin.pdf"}

			// Notice that the :file_name parameter in the route becomes a separate
			// attribute of can.route.  This "wildcard" enables listening for
			// any two-parameter route that begins with 'files'.  /files/test.html
			// and /files/moose would also route here.
    },

		// A useless route handler that will never be called.
    "/files/joker.pdf route": function(data){
			// <a href="/files/joker.pdf">Smile!</a>
			// {route:"files/joker.pdf"}

			// Why is it useless? Because, while the above href could potentially
			// trigger this route event, the directly preceding route with the
			// :file_name parameter will respond because it comes first in the code.
    },

    // Three parameters, one a wildcard.
    "/admin/contestants/:id route": function(data){
			// <a href="/admin/contestants/1">The price is wrong, Bob.</a>
			// {route:"admin/contestants/:id", id:1}
    },

		// Three parameters, two wildcards.
    "/admin/:page/:anchor route": function(data){
			// <a href="/admin/testimonials/steve">Steve's Testimonial</a>
			// {route:"admin/:page/:anchor", page:"testimonials", anchor:"steve"}

			// Technically, if the route just before this one was taken out of the code,
			// this route would respond to links like /admin/users/1.  In that case the
			// route attributes would look like this:

			// <a href="/admin/contestants/1">The price is wrong, Bob.</a>
			// {route:"admin/:page/:anchor", page:"contestants", anchor:"1"}

			// But, because the preceding route exists, this route will respond to all
			// three-parameter routes that have the first parameter of admin except for ones
			// where the second parameter is users (ones that start with '/admin/users/'),
			// which are picked up by the preceding route.
    },

## Watch for errors inside route handlers.

Any error inside of a route handler will cause a page refresh before the error is shown.  For example:

		"/admin/users route": function(data){

			// This will cause problems.
			console.log(nonExistentVariable);

			// This will cause problems
			var users = someFunctionWithAnErrorInside();

			// This one won't cause problems.
			console.log(data);
    }

In an error-free route handler, all links matching the route would not result in a page refresh, but instead would result in a pushstate change.  The errors in the example will cause the page to refresh.  The code will run after the refresh and show the error message.

## Planning route structure

Complications can arise if your route structure mimics the folder structure inside your app's public directory.  For example, if you have a folder structure like the one in this url for your admin app...

`/admin/users/list.js`

... using a route of /admin/users on the same page that uses the list.js file will require the use of a trailing slash on all routes and links.  The browser already learned that '/admin/users' is folder.  Because folders were originally denoted by a trailing slash in a url, the browser will correct the url to be '/admin/users/'.  While it is possible to add the trailing slash in routes and listen for them, any link to the page that omits the trailing slash will not trigger the route handler.
