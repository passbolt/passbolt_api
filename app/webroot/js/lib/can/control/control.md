@constructor can.Control
@parent canjs
@download can/route
@test can/route/test.html
@test can/control/test.html
@inherits can.Construct
@description widget factory with declarative event binding.
@group can.Control.plugins plugins
@link ../docco/control/control.html docco

@description Create organized, memory-leak free, rapidly performing,
stateful controls with declarative event binding. Use `can.Control` to create UI 
controls like tabs, grids, and context menus,
and organize them into higher-order business rules with
[can.route]. It can serve as both a traditional view and a traditional controller.

@signature `can.Control( [staticProperties,] instanceProperties )`

Create a new, extended, control constructor 
function. This functionality is inherited from [can.Construct] and is deprecated in favor of using 
[can.Control.extend]. 

@param {Object} [staticProperties] An object of properties and methods that are added the control constructor 
function directly. The most common property to add is [can.Control.defaults].

@param {Object} instanceProperties An object of properties and methods that belong to 
instances of the `can.Control` constructor function. These properties are added to the
control's `prototype` object. Properties that
look like event handlers (ex: `"click"` or `"li mouseenter"`) are setup
as event handlers (see [Listening to events](#section_Listeningtoevents)).

@return {function(new:can.Construct,element,options)} A control constructor function that has been
extended with the provided `staticProperties` and `instanceProperties`.


@signature `new can.Control( element, options )`

Create an instance of a control. [can.Control.prototype.setup] processes
the arguments and sets up event binding. Write your initialization
code in [can.Control.prototype.init]. Note, you never call `new can.Control()` directly,
instead, you call it on constructor functions extended from `can.Control`.

@param {HTMLElement|can.NodeList|CSSSelectorString} element Specifies the element the control 
will be created on.

@param {Object} [options] Option values merged with [can.Control.defaults can.Control.defaults]
and set as [can.Control.prototype.options this.options].

@return {can.Control} A new instance of the constructor function extending can.Control.

@body

## The Control Lifecycle

The following walks through a control's lifecycle
with an example todo list widget.  It's broken up into the following
lifecycle events:

 - Extending a control
 - Creating a control instance
 - Listening to events
 - Destroying a control

## Extending a control

The following example builds up a basic todos widget for listing 
and completing todo items. Start by creating a control constructor 
function of your own by extending [can.Control] and defining an instance init method.

    var Todos = can.Control.extend({
      init: function( element, options ) { ... }
    });

## Creating a control instance

Create an instance of the Todos control on the `todos` element with:

    var todosControl = new Todos( '#todos', {} );

The control's associated [can.ejs EJS] template looks like:

    <% todos.each(function( todo ) { %>
      <li <%= (el) -> el.data( 'todo', todo ) %> >
        <%= todo.attr( 'name' ) %>
        <a href="javascript://" class="destroy">
      </li>
    <% }) %>

### `init(element, options)`

[can.Control.prototype.init] is called with the below arguments when new instances of [can.Control] are created:

- __element__ - The wrapped element passed to the 
                control. Control accepts a
                raw HTMLElement, a CSS selector, or a NodeList. This is
                set as `this.element` on the control instance.
- __options__ - The second argument passed to new Control, extended with
                the can.Control's static __defaults__. This is set as 
                `this.options` on the control instance. Note that static is used
                formally to indicate that _default values are shared across control instances_.

Any additional arguments provided to the constructor will be passed as normal. Use [can.view] to produce a document fragment
from your template and inject it in the passed element. Note that the `todos` parameter passed to [can.view] below
is an instance of [can.List]:

    var Todos = can.Control.extend({

      //defaults are merged into the options arg provided to the constructor
      defaults : { view: 'todos.ejs' }

    }, {
      init: function( element , options ) {

        //create a pointer to the control's scope
        var self = this;

        //run the Todo model's .findAll() method to produce a can.List
        Todo.findAll( {}, function( todos ) {

            //create a document fragment with can.view
            //and inject it into the provided element's body
    		self.element.html( can.view(self.options.view, todos) );
        });
      }
    });
    
    // create a Todos Control with default options
    new Todos( document.body.firstElementChild );
    
    // overwrite the template default
    new Todos( '#todos', { template: 'specialTodos.ejs' } );

### `this.element`

[can.Control::element] is the 
NodeList consisting of the element the control is created on. 

    var todosControl = new Todos( document.body.firstElementChild );
    todosControl.element[0] //-> document.body.firstElementChild

Each library wraps elements differently. If you are using jQuery, for example,
the element is wrapped with `jQuery( element )`.

### `this.options`

[can.Control::options] is the second argument passed to 
`new can.Control()`, merged with the control's static __defaults__ property.

## Listening to events

Control automatically binds prototype methods that look
like event handlers. Listen to __click__'s on `<li>` elements like:

    var Todos = can.Control.extend({
      init: function( element , options ) {...},

      'li click': function( li, event ) {
        console.log( 'You clicked', li.text() );
        
        // let other controls know what happened
        li.trigger( 'selected' );
      }
    });

When an `<li>` is clicked, `"li click"` is called with:

- The library-wrapped __element__ that was clicked
- The __event__ data

Control uses event delegation, so you can add `<li>`s without needing to rebind
event handlers.

To destroy a todo when its `<a href="javascript://" class="destroy">` link 
is clicked:

    var Todos = can.Control.extend({
      init: function( element, options ) {...},
      
      'li click': function( li ) {...},
      
      'li .destroy click': function( el, ev ) {
        // get the li element that has todo data
        var li = el.closest( 'li' );
      
        // get the model
        var todo = li.data( 'todo' );
  
        //destroy it
        todo.destroy();
      }
    });

When the todo is destroyed, EJS's live binding will remove its LI automatically.

### Templated Event Handlers Part 1 `"{eventName}"`

Customize event handler behavior with `"{NAME}"` in
the event handler name.  The following allows customization 
of the event that destroys a todo:

    var Todos = can.Control.extend({
      init: function( element , options ) { ... },
      
      'li click': function( li ) { ... },
      
      'li .destroy {destroyEvent}': function( el, ev ) { 
        // previous destroy code here
      }
    });

    // create Todos with this.options.destroyEvent
    new Todos( '#todos', { destroyEvent: 'mouseenter' } );

Values inside `{NAME}` are looked up on the control's `this.options` first,
and then the `window`. For example, we could customize it instead like:

    var Todos = can.Control.extend({
      init: function( element , options ) { ... },
      
      'li click': function( li ) { ... },
  
      'li .destroy {Events.destroy}': function( el, ev ) { 
        // previous destroy code here
      }
    });

    // Events config
    Events = { destroy: 'click' };

    // Events.destroy is looked up on the window.
    new Todos( '#todos' );

The selector can also be templated.

    var Todos = can.Control.extend({
      init: function( element , options ) { ... },
      
      '{listElement} click': function( li ) { ... },
      
      '{listElement} .destroy {destroyEvent}': function( el, ev ) { 
        // previous destroy code here
      }
    });

    // create Todos with this.options.destroyEvent
    new Todos( '#todos',  { 
      destroyEvent: 'mouseenter', 
      listElement: 'li' 
    } );

### Templated Event Handlers Part 2 `"{objectName}"`

Control can also bind to objects other than `this.element` with
templated event handlers.  This is _critical_
for avoiding memory leaks that are so common among MVC applications.  

If the value inside `{NAME}` is an object, Control will bind to that
object to listen for events. For example, the following tooltip listens to 
clicks on the window:

    var Tooltip = can.Control.extend({
      '{window} click': function( el, ev ) {
        // hide only if we clicked outside the tooltip
        if ( !this.element.has( ev.target ) ) {
          this.element.remove();
        }
      }
    });

    // create a Tooltip
    new Tooltip( $( '<div>INFO</div>' ).appendTo( el ) );
    
This is convenient when listening for model changes. If EJS were not
taking care of removing `<li>`s after their associated models were destroyed,
we could implement it in `Todos` like:

    var Todos = can.Control.extend({
      init: function( element, options ) {...},
      
      'li click': function( li ) {...},
      
      'li .destroy click': function( el, ev ) {
        // get the li element that has todo data
        var li = el.closest( 'li' );
      
        // get the model
        var todo = li.data( 'todo' );
  
        //destroy it
        todo.destroy();
      },
      
      '{Todo} destroyed': function( Todo, ev, todoDestroyed ) {
        // find where the element
        var index = this.todosList.indexOf( todoDestroyed );
        this.element.children( ':nth-child(' + ( index + 1 ) + ')' )
                    .remove();
      }
    });

    new Todos( '#todos' );

### `on()`

[can.Control::on] rebinds a control's event handlers. This is useful when you want
to listen to a specific model and change it:

    var Editor = can.Control.extend({
      todo: function( todo ) {
        this.options.todo = todo;
        this.on();
        this.setName();
      },
      
      // a helper that sets the value of the input
      // to the todo's name
      setName: function() {
        this.element.val( this.options.todo.name );
      },
      
      // listen for changes in the todo
      // and update the input
      '{todo} updated': function() {
        this.setName();
      },

      // when the input changes
      // update the todo instance
      'change': function() {
        var todo = this.options.todo;
        todo.attr( 'name', this.element.val() );
        todo.save();
      }
    });

    var todo1 = new Todo({ id: 6, name: 'trash' }),
        todo2 = new Todo({ id: 6, name: 'dishes' });

    // create the editor;
    var editor = new Editor( '#editor' );

    // show the first todo
    editor.todo( todo1 );

    // switch it to the second todo
    editor.todo( todo2 );
    

## Destroying a control

[can.Control::destroy] unbinds a control's
event handlers and releases its element, but does not remove 
the element from the page. 

    var todo = new Todos( '#todos' );
    todo.destroy();

When a control's element is removed from the page
__destroy__ is called automatically.

    new Todos( '#todos' );
    $( '#todos' ).remove();
    
All event handlers bound with Control are unbound when the control 
is destroyed (or its element is removed).

_Brief aside on destroy and templated event binding. Taken 
together, templated event binding, and control's automatic
clean-up make it almost impossible 
to write leaking applications. An application that uses
only templated event handlers on controls within the body
could free up all 
data by calling `$(document.body).empty()`._

## Tabs Example

Here is an example of how to build a simple tab widget using can.Control:

<iframe style="width: 100%; height: 300px"
        src="http://jsfiddle.net/donejs/kXLLt/embedded/result,html,js,css" 
        allowfullscreen="allowfullscreen" 
        frameborder="0">JSFiddle</iframe>