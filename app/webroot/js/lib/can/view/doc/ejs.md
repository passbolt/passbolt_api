@function can.view.ejs ejs
@parent can.view.static

@deprecated {2.1} Use [can.ejs] instead.

@signature `can.view.ejs( [id,] template )`

Register an EJS template string and create a renderer function.

    var renderer = can.view.ejs("<h1><%= message %></h1>");
    renderer({message: "Hello"}) //-> docFrag[ <h1>Hello</h1> ]

@param {String} [id] An optional ID to register the template.


    can.view.ejs("greet","<h1><%= message %></h1>");
    can.view("greet",{message: "Hello"}) //-> docFrag[<h1>Hello</h1>]

@param {String} template An EJS template in string form.
@return {can.view.renderer} A renderer function that takes data and helpers.


@body
`can.view.ejs([id,] template)` registers an EJS template string
for a given id programatically. The following
registers `myViewEJS` and renders it into a documentFragment.

    can.view.ejs('myViewEJS', '<h2><%= message %></h2>');

    var frag = can.view('myViewEJS', {
        message : 'Hello there!'
    });

    frag // -> <h2>Hello there!</h2>

To convert the template into a render function, just pass
the template. Call the render function with the data
you want to pass to the template and it returns the
documentFragment.

    var renderer = can.view.ejs('<div><%= message %></div>');
    renderer({
       message : 'EJS'
    }); // -> <div>EJS</div>