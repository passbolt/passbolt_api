@function can.Map.prototype.define.remove remove
@parent can.Map.prototype.define

Called when an attribute is removed.

@signature `remover( currentValue )`

@return {*|false} If `false` is returned, the value is not removed.

@body 

## Use

The following prevents removing the _prop_ attribute if someone tries to remove the value 0:


    define: {
      prop: {
        remove: function( currentVal ){
          return currentVal !== 0;
        }
      }
    }

