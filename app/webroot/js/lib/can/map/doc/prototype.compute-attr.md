@property {can.compute} can.Map.prototype.COMPUTE-ATTR
@parent can.Map.prototype 0

@description Specify an attribute that is computed from other attributes.

@option {can.compute} A compute that reads values on instances of the
map and returns a derived value.  The compute may also be a getter-setter
compute and able to be passed a value.

@body

## Use

When extending [can.Map], if a prototype property is a [can.compute]
it will setup that compute to behave like a normal attribute. This means
that it can be read and written to with [can.Map::attr attr] and bound to
with [can.Map::bind bind].

The following example makes a `fullName` attribute on `Person` maps:

    var Person = can.Map.extend({
        fullName: can.compute(function(){
            return this.attr("first") + " " + this.attr("last")
        })
    })

    var me = new Person({first: "Justin", last: "Meyer"})

    me.attr("fullName") //-> "Justin Meyer"

    me.bind("fullName", function(ev, newValue, oldValue){
        newValue //-> Brian Moschel
        oldValue //-> Justin Meyer
    })

    me.attr({first: "Brian", last: "Moschel"})

## Getter / Setter computes

A compute's setter will be called if [can.Map::attr attr] is
used to set the compute-property's value.

The following makes `fullName` able to set `first` and `last`:

    var Person = can.Map.extend({
        fullName: can.compute(function(newValue){
            if( arguments.length ) {
                var parts = newValue.split(" ");
                this.attr({
                    first: parts[0],
                    last:  parts[1]
                });
            } else {
                return this.attr("first") + " " + this.attr("last");
            }
        })
    })

    var me = new Person({first: "Justin", last: "Meyer"})

    me.attr("fullName", "Brian Moschel")
    me.attr("first") //-> "Brian"
    me.attr("last")  //-> "Moschel"

## Alternatives

[can.mustache] and [can.ejs] will automatically convert any function
read in the template to a can.compute. So, simply having a fullName
function like:

    var Person = can.Map.extend({
        fullName: function(){
            return this.attr("first") + " " + this.attr("last")
        }
    })
    var me = new Person({first: "Justin", last: "Meyer"})

Will already be live-bound if read in a template like:

    {{me.fullName}}
    // or
    <%= me.attr("fullName") %>

The [can.Map.setter setter] plugin can also provide similar functionality as
Getter/Setter computes.
