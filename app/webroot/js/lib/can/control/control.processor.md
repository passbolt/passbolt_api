@typedef {function(HTMLElement,String,CSSSelectorString,Function,can.Control)} can.Control.processor(element,eventName,selector,handler,control)

@description A function that handles the binding and unbinding of a [can.Control]'s declarative event method.

@param {HTMLElement} element the control's element or the object 
specified by the templated event handler (`"{object}"`).

@param {String} eventName The event type.

@param {CSSSelectorString} selector The selector preceding the event in the binding used on the Control.

@param {function(this:can.Control,Object,Event)} handler(element, event) The callback function being bound.

@option {Object} element foo
@option {Event} event bar

@param {can.Control} control The Control the event is bound on.

@return {function()} A callback function that unbinds any event handlers bound within this processor.
