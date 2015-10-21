@function can.frag
@parent can.util

Convert a String, HTMLElement, documentFragment, or contentArray into a documentFragment.

@param {String|HTMLElement|documentFragment|can.contentArray} item

@return {documentFragment}

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
