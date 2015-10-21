@page can.List.extend extend
@parent can.List.static

@function extend

@signature `can.List.extend([name,] [staticProperties,] instanceProperties)`

Creates a new extended constructor function. Learn more at [can.Construct.extend].

@param {String} [name] If provided, adds the extened List constructor function to the window at the given name.

@param {Object} [staticProperties] Properties and methods directly on the constructor function. The most common property to set is [can.List.Map].

@param {Object} [instanceProperties] Properties and methods on instances of this list type.