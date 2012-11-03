@page jQuery.event.default
@parent jquerypp

`jQuery.event.default` allows you to perform default actions as a result of an event.

Event based APIs are a powerful way of exposing functionality of your widgets.  It also fits in 
quite nicely with how the DOM works.


Like default events in normal functions (e.g. submitting a form), synthetic default events run after
all event handlers have been triggered and no event handler has called
preventDefault or returned false.

To listen to a default event, just prefix the event with `default` namespace:

    $("div").on("default.show", function(ev){ ... });
    $("ul").on("default.activate", "li", function(ev){ ... });


## Example

Default events are useful in cases where you want to provide an event based
API for users of your widgets.  Users can simply listen to your synthetic events and 
prevent your default functionality by calling preventDefault.  

In the example below, the tabs widget provides a show event.  Users of the 
tabs widget simply listen for show, and if they wish for some reason, call preventDefault 
to avoid showing the tab.

In this case, the application developer doesn't want to show the second 
tab until the checkbox is checked. 

@demo jquery/event/default/defaultjquery.html
