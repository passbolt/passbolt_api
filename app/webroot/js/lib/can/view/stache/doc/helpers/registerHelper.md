@function can.stache.registerHelper registerHelper
@description Register a helper.
@parent can.stache.static

@signature `can.stache.registerHelper(name, helper)`
@param {String} name The name of the helper.
@param {can.stache.helper} helper The helper function.

@body

Registers a helper with the Mustache system.
Pass the name of the helper followed by the
function to which Mustache should invoke.
These are run at runtime.
