@description Create a random number or selection.
@function can.fixture.rand rand
@parent can.fixture
@signature `can.fixture.rand([min,] max)`
@param {Number} [min=0] The lower bound on integers to select.
@param {Number} max The upper bound on integers to select.
@return {Number} A random integer in the range [__min__, __max__).

@signature `can.fixture.rand(choices, min[ ,max])`
@param {Array} choices An array of things to choose from.
@param {Number} min The minimum number of times to choose from __choices__.
@param {Number} [max=min] The maximum number of times to choose from __choices__.
@return {Array} An array of between __min__ and __max__ random choices from __choices__.

@body
`can.fixture.rand` creates random integers or random arrays of other arrays.

## Examples

    var rand = can.fixture.rand;
    
    // get a random integer between 0 and 10 (inclusive)
    rand(11);
    
    // get a random number between -5 and 5 (inclusive)
    rand(-5, 6);
    
    // pick a random item from an array
    rand(["j","m","v","c"],1)[0]
    
    // pick a random number of items from an array
    rand(["j","m","v","c"])
    
    // pick 2 items from an array
    rand(["j","m","v","c"],2)
    
    // pick between 2 and 3 items at random
    rand(["j","m","v","c"],2,3)
