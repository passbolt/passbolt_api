@typedef {function(this:can.stache.context,...*,can.stache.sectionOptions){}} can.stache.helper(arg,options)
@parent can.stache.types 

@description A helper function passed to [can.stache.registerHelper].

@param {...*|can.compute} [arg] Arguments passed from the tag. After the helper
name, any space seperated [can.stache.key keys], numbers or 
strings are passed as arguments. [can.stache.key Keys] that 
read an observable value are passed as [can.compute]'s.

@param {can.stache.helperOptions} options An options object
that gets populated with optional:

- `fn` and `inverse` section rendering functions 
- a `hash` object of the maps passed to the helper 

@this {can.stache.context} The context the helper was 
called within.

@return {documentFragment|String|can.contentArray|function(HTMLElement)} The content to be inserted into
the template.

@body

## Use

The following template:

    <p>{{madLib "Lebron James" verb 4}}</p>

Rendered with

    {verb: "swept"}

Will call a `madLib` helper with the following arguements.

    can.stache.registerHelper('madLib', 
      function(subject, verb, number){
        // subject -> "Lebron James"
        // verb -> "swept"
        // number -> 4
    });
    

While keys are normally resolved as basic objects like strings or numbers, 
there are special cases where they act differently than a normal 
tag. Whenever a [can.compute] or function 
object is an argument for a helper, the original object is used 
as the argument instead of the value that the function returns.

If a [can.stache.key] represents a [can.Map] attribute,
it is converted to a [can.compute] getter/setter 
function. This enables 2-way binding helpers.  

For example, the following helper two-way binds an input element's
value to a [can.compute]:

    can.stache.registerHelper('value',function(value){
        return function(el){
          value.bind("change",function(ev, newVal){
            el.value = newVal;
          })
          el.onchange = function(){
            value(this.value);
          }
          el.value = value();
        }
    });
    
And used by the following template:

    <input type="text" {{me.value name}}/>
    
And rendered with:
    
    {me: new can.Map({name: "Payal"})}


## Returning an element callback function

If a helper returns a function, that function is called back after
the template has been rendered into DOM elements. This can 
be used to create stache tags that have rich behavior. 

If the helper is called __within a tag__ like:

    <ul {{sortable}}/>

The returned function is called with the `<ul>` element:

    can.stache.registerHelper("sortable",function(){
      return function(el){
        $(el).slider();
      }
    });

If the helper is called __between tags__ like:

    <ul>{{items}}</ul>
    
The returned function is called with a temporary element. The 
following helper would be called with a temporary `<li>` element:

    can.stache.registerHelper("items",function(){
      return function(li){
        
      }
    });

The temporary element depends on the parent element. The default temporary element
is a `<span>` element.



