@function can.view.target

Create a document fragment that can be cloned but have callbacks be
called quickly on elements within the cloned fragment.


@body

    var target = can.view.target([
      {
        tag: "h1",
        callbacks: [function(data){
          this.className = data.className
        }],
        children: [
          "Hello ",
          function(){
            this.nodeValue = data.message
          }
        ]
      },
    ]);
    
    // target.clone -> <h1>|Hello||</h1>
    // target.paths -> path: [0], callbacks: [], children: {paths: [1], callbacks:[function(){}]}
    
    var frag = target.hydrate({className: "title", message: "World"});
    
    frag //-> <h1 class='title'>Hello World</h1>
