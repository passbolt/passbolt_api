@property {*} can.Map.prototype.DEFAULT-ATTR
@parent can.Map.prototype 1

@description Specify a default property and value.

@option {*} A value of any type other than a function that will
be set as the `DEFAULT-ATTR` attribute's value.

@body

## Use

When extending [can.Map], if a prototype property is not a function,
it is used as a default value on instances of the extended Map.  For example:

```
var Paginate = can.Map.extend({
    limit: 20,
    offset: 0,
    next: function(){
        this.attr("offset", this.attr("offset")+this.attr("limit"))
    }
});

var paginate = new Paginate({limit: 30});

paginate.attr("offset") //-> 0
paginate.attr("limit")  //-> 30

paginate.next();

paginate.attr("offset") //-> 30
```
