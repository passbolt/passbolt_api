@function can.view.parser

Parse HTML and mustache tokens.

@param {String|Object} html A mustache and html string to parse or an intermediate object the represents a previous parsing.
@param {Object}  handler An object of function call backs.
@param {Boolean} [returnIntermediate=false] If true, returns a JS object representation of the parsing.


@body

    can.view.parser("<h1> ....", {
    	start:     function( tagName, unary ){},
		end:       function( tagName, unary ){},
		close:     function( tagName ){},
		attrStart: function( attrName ){},
		attrEnd:   function( attrName ){},
		attrValue: function( value ){},
		chars:     function( value ){},
		comment:   function( value ){},
		special:   function( value ){},
		done:      function( ){}
    })
