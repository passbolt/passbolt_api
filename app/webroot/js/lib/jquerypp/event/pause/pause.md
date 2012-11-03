@page jQuery.event.pause
@parent jquerypp

`jQuery.event.pause` adds the ability to pause and  resume events.

    $('#todos').on('show', function(ev){
      ev.pause();
      
      $(this).load('todos.html', function(){
        ev.resume();
      });
    })

Calling `event.pause()` works similar to
[event.stopImmediatePropagation()](http://api.jquery.com/event.stopImmediatePropagation/) and
stops calling other event handlers for the  event.
When `event.resume()` is called, it will continue calling events on event handlers
after the 'paused' event handler.

Pause-able events complement the [jQuery.event.default default]
event plugin, providing the ability to easy create widgets with
an asynchronous API.  

## Limitations

The element and event handler that the <code>pause</code> is within can not be removed before 
resume is called.

## Example

The following example implements a `tabs` widget using [CanJS](http://canjs.us) which uses `jQuery.fn.triggerAsync` to hide the tab:

    can.Control("Tabs", {
      init : function (el) {
        $(el).children("li:first").addClass('active');
        var tab = this.tab;
        this.element.children("li:gt(0)").each(function () {
          tab($(this)).hide()
        })
      },
      tab : function (li) {
        return $(li.find("a").attr("href").match(/#.*/)[0])
      },
      "li click" : function (el, ev) {
        ev.preventDefault();
        var active = this.element.find('.active')
        old = this.tab(active),
            cur = this.tab(el);
        old.triggerAsync('hide', function () {
          active.removeClass('active')
          old.slideUp(function () {
            el.addClass('active')
            cur.slideDown()
          });
        })
      }
    })

Each tab panel contains a form to input data and has a `Dirtybit` control attached to it which keeps track if the form has been saved or not. When the form data changes and you go to another tab The `Saver` widget will pause the `hide` event and show a modal that allows you to save the form, or cancel to stay in the current tab. Saving the data will send a POST Ajax request and resume the event when it returns:

    can.Control("Saver", {
    }, {
      " hide" : function (el, ev) {
        if (el.hasClass('dirty')) {
          ev.pause()
          new Modal('#modal', {
            yes : function () {
              var save = $('<span>Saving</span>').appendTo(el);
              $.post("/update", el.serialize(), function () {
                save.remove();
                el.trigger('set');
                ev.resume();
              })
            },
            no : function () {
              ev.resume();
            },
            cancel : function () {
              ev.preventDefault();
              ev.resume();
            }
          })
        }
      }
    });

@demo jquery/event/pause/pause.html