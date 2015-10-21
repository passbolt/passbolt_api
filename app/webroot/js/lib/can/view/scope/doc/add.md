@function can.view.Scope.add add
@parent can.view.Scope.prototype

@param {*} context The context of the new scope object.

@return {can.view.Scope}  A scope object.

@body

## Use

`scope.add(context)` creates a new scope object that
first looks up values in context and then in the
parent `scope` object.

    var list = [{name: "Justin"},{name: "Brian"}],
        justin = list[0];

    var curScope = new can.view.Scope(list).add(justin);

    curScope.attr("name") //-> "Justin"
    curScope.attr("length") //-> 2