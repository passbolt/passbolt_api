@typedef {{get: function, set: function, on: function, off: function}} computeSettings computeSettings
@release 2.1
@parent can.compute

@option {function} get A function that retrieves and returns the current value of the compute.
@option {function(*,*):*} set(newVal,oldVal) A function that is used when setting a new value of the compute.

A function that is called when a compute is called with an argument. The function is passed
the first argumented passed to [can.computed compute] and the current value. If
`set` returns a value, it is used to compare to the current value of the compute. Otherwise,
`get` is called to get the current value of the compute and that value is used
to determine if the compute has changed values.

`newVal` is the value being set, while `oldVal` is the previous value in the compute.

@option {function(function)} on(updated) Called to setup binding to dependency events. Call `updated` when the compute's value needs to be updated.

@option {function(function)} off Called to teardown binding.
