@typedef {{}} can.route.binding

@option {String} root The starting point of 
the url to match. For `hashchange`, the value
is "#!". For `pushstate`, the value is `/`.  This can
be overwritten before [can.route.ready] is called like:

    can.route.bindings.pushstate.root = "/site/"

@option {String} querySeparator Specifies the seperator
between the path part of the url and the query (also known as search)
part of the url. For `hashchange`, the value
is `"&"`. For `pushstate`, the value is `"?"`.

@option {RegExp} paramsMatcher A regular expression that is used
to identify the `key=value` pairs in the query part of the url.

@option {function():String} matchingPartOfURL Reads the url and returns the
part that is used for matching routes.

@option {function(path):String} setURL Called with the 
serialized can.route data after a route has changed.
Returns what the url has been updated to.

@option {function} bind Attaches listeners to the document to know
when the url has changed.  Typically `bind` is called when [can.route.ready] is called.

@option {function} unbind Tears down the bindings to the document.


