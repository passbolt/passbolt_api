@page can.Observe.delegate
@parent can.Observe
@plugin can/observe/delegate
@test can/observe/delegate/qunit.html
@download http://donejs.com/can/dist/can.observe.delegate.js

The __delegate__ plugin allows you to listen to more specific event changes on 
[can.Observe Observes].  It allows you to specify:

 - the __attribute__ or __attributes__ - that you want to listen to and optionally the __value__ you want it to match
 - the __type__ of event (add,set,remove,change)

Listen to specific event changes with 
<code>[can.Observe::delegate delegate]\(selector, event, handler(ev,newVal,oldVal,from)\)</code> :


	// create an observable
	var observe = new can.Observe({
      name : {
        first : "Justin Meyer"
      }
    })
  	var handler;
    //listen to changes on a property
    observe.delegate("name.first","set", 
      handler = function(ev, newVal, oldVal, prop){
      
      this   //-> "Justin"
      ev.currentTarget //-> observe
      newVal //-> "Justin Meyer"
      oldVal //-> "Justin"
      prop   //-> "name.first"
    });
 
    // change the property
    observe.attr('name.first',"Justin")

Delegate will listen on the object until you 
call <code>[can.Observe::undelegate undelegate]\(selector, event, handler\)</code> to remove the event handler.

	observe.undelegate("name.first","set", handler );
 
