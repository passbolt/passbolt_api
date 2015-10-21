@typedef {function} can.compute.asyncComputer(currentVal, setVal)
@parent can.compute
@release 2.1
@hide

A function that determines a value for an [can.compute.async async compute].

@option {function} The function callback to [can.compute.async] that determines
the value of the compute.

@param {*} [lastSetValue] The last set value of the compute.  This should be returned
if you are doing an in-place compute. 

@param {function(*)} [setVal(newVal)] Called to update the value 
of the compute at a later time. 

@return {*} If a `setVal` argument is not provided, the return value
is set as the current value of the compute.  If `setVal` is provided and
undefined is returned, the current value remains until `setVal` is called.
