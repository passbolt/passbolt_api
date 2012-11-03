@class can.Control
@parent canjs
@plugin can/control
@download  http://jmvcsite.heroku.com/pluginify?plugins[]=can/control/control.js
@test can/control/qunit.html
@inherits can.Construct
@description widget factory with declarative event binding.

can.Control helps create organized, memory-leak free, rapidly performing,
stateful controls. Use it to create UI controls like tabs, grids, and context menus,
and organize them into higher-order business rules with
[can.route]. It can serve as both a traditional view and a traditional controller.

## Todo Example

Here is an example of how to build a simple tab widget using can.Control:

@demo can/control/control.html

## Creating a Control

The following example builds up a basic todos widget for listing 
and completing todo items. Start by creating a control constructor 
function of your own by extending can.Control:

    var Todos = can.Control({
      init: function( element, options ) {
        var self = this;
        Todo.findAll( {}, function( todos ) {
          self.element.html( 'todos.ejs', todos );
        });
      }
    });

Create an instance of the Todos control on the `todos` element with:

    var todosControl = new Todos( '#todos', {} );

The control's associated [can.EJS EJS] template looks like:

    <% list( todos, function( todo ) { %>
      <li <%= (el) -> el.data( 'todo', todo ) %> >
        <%= todo.attr( 'name' ) %>
        <a href="javascript://" class="destroy">
      </li>
    <% }) %>

## init `can.Control.prototype.init( element, options )`

`init` is called when a new can.Control instance is created. It is called with:

- __element__ - The wrapped element passed to the 
                control. Control accepts a
                raw HTMLElement, a CSS selector, or a NodeList. This is
                set as __this.element__ on the control instance.
- __options__ - The second argument passed to new Control, extended with
                the can.Control's static __defaults__. This is set as 
                __this.options__ on the control instance.

and any other arguments passed to `new can.Control()`. For example:

    var Todos = can.Control({
      defaults : { view: 'todos.ejs' }
    }, {
      init: function( element , options ) {
        var self = this;
        Todo.findAll( {}, function( todos ) {
    		self.element.html( self.options.view, todos );
        });
      }
    });
    
    // create a Todos with default options
    new Todos( document.body.firstElementChild );
    
    // overwrite the template option
    new Todos( $( '#todos' ), { template: 'specialTodos.ejs' } );

## element `this.element`

[can.Control::element] is the 
NodeList consisting of the element the control is created on. 

    var todosControl = new Todos( document.body.firstElementChild );
    todosControl.element[0] //-> document.body.firstElementChild

Each library wraps elements differently. If you are using jQuery, for example,
the element is wrapped with `jQuery( element )`.

## options `this.options`

[can.Control::options] is the second argument passed to 
`new can.Control()`, merged with the control's static __defaults__ property.

## Listening to events

Control automatically binds prototype methods that look
like event handlers. Listen to __click__s on `<li>` elements like:

    var Todos = can.Control({
      init: function( element , options ) {
        var self = this;
        Todo.findAll( {}, function( todos ) {
          self.element.html( self.options.template, todos );
        });
      },

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

    var Todos = can.Control({
      init: function( element, options ) {
        var self = this;
        Todo.findAll( {}, function( todos ) {
          self.element.html( self.options.template, todos );
        });
      },
      
      'li click': function( li ) {
        li.trigger( 'selected', li.model() );
      },
      
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

## Templated Event Handlers Part 1 `"{eventName}"`

Customize event handler behavior with `"{NAME}"` in
the event handler name.  The following allows customization 
of the event that destroys a todo:

    var Todos = can.Control( 'Todos', {
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

    var Todos = can.Control( 'Todos', {
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

    var Todos = can.Control( 'Todos', {
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

## Templated Event Handlers Part 2 `"{objectName}"`

Control can also bind to objects other than `this.element` with
templated event handlers.  This is _critical_
for avoiding memory leaks that are so common among MVC applications.  

If the value inside `{NAME}` is an object, Control will bind to that
object to listen for events. For example, the following tooltip listens to 
clicks on the window:

    var Tooltip = can.Control({
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

    var Todos = can.Control({
      init: function( element, options ) {
        var self = this;
        Todo.findAll( {}, function( todos ) {
          self.todosList = todos;
          self.element.html( self.options.template, todos );
        });
      },
      
      'li click': function( li ) {
        li.trigger( 'selected', li.model() );
      },
      
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

## destroy `control.destroy()`

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

## on `control.on()`

[can.Control::on] rebinds a control's event handlers. This is useful when you want
to listen to a specific model and change it:

    var Editor = can.Control({
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

Here's the full todo list manager in action:

@iframe can/test/demo.html 400
