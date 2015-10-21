@function can.view.mustache mustache
@parent can.view.static

@deprecated {2.1} Use [can.mustache] instead.

@signature `can.mustache( [id,] template )`

Register a Mustache template string and create a renderer function.

    var renderer = can.mustache("<h1>{{message}}</h1>");
    renderer({message: "Hello"}) //-> docFrag[ <h1>Hello</h1> ]

@param {String} [id] An optional ID for the template.

    can.view.ejs("greet","<h1>{{message}}</h1>");
    can.view("greet",{message: "Hello"}) //-> docFrag[<h1>Hello</h1>]

@param {String} template A Mustache template in string form.

@return {can.view.renderer} A renderer function that takes data and helpers.

@body

`can.mustache([id,] template)` registers an Mustache template string
for a given id programatically. The following
registers `myStache` and renders it into a documentFragment.

     can.viewmustache('myStache', '<h2>{{message}}</h2>');

     var frag = can.view('myStache', {
        message : 'Hello there!'
     });

     frag // -> <h2>Hello there!</h2>

To convert the template into a render function, just pass
the template. Call the render function with the data
you want to pass to the template and it returns the
documentFragment.

    var renderer = can.mustache('<div>{{message}}</div>');
    renderer({
        message : 'Mustache'
    }); // -> <div>Mustache</div>