@typedef {Array<documentFragment|Array|Element>} can.contentArray contentArray

@description An array of things that can be converted to a document fragment 
with [can.frag]. ContentArray's can be returned by [can.stache.helper helper methods].

@option {Array<documentFragment|String|Element|can.contentArray>}

An array that has the following as possible children:

 - documentFragment
 - String
 - Element
 - Other contentArray's

@body

## Use

ContentArray's can be used to combine multiple HTMLElements into a single document fragment.  For example:

    var p = document.createElement("p");
    p.innerHTML = "Welcome to <b>CanJS</b>";
    var contentArray = ["<h1>Hi There</h1>", p];
    var frag = can.frag( contentArray )

`frag` will be a documentFragment with the following elements:

    <h1>Hi There</h1>
    <p>Wlecome to <b>CanJS</b></p>

