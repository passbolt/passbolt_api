@function can.computed compute
@parent can.compute

@signature `compute( [newVal] )`

@param {*} [newVal] If `compute` is called with an argument, the first argument is used
to set the compute to a new value. This may trigger a 
`"change"` event that can be listened for with [can.computed.bind].

If the compute is called without any arguments (`compute()`), it simply returns
the current value of the compute.

@return {*} The current value of the compute.
