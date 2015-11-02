

(function (__global){
  'use strict';

  if(!__global.console){
    __global.console = { log : __global.dump || function (){} };
  }


  /**
   * Describe a block if the bool is true.
   * Will skip it otherwise.
   * @param bool
   * @returns {Function} describe or describe.skip
   */
  function describeIf(bool) {
    return (bool ? describe : describe.skip)
      .apply(null, Array.prototype.slice.call(arguments, 1));
  }

  __global.describeIf = describeIf;

}(typeof window != 'undefined' ? window : global));


