@property {String} can.Model.id id
@parent can.Model.static
The name of the id field.  Defaults to `'id'`. Change this if it is something different.

For example, it's common in .NET to use `'Id'`.  Your model might look like:

```
Friend = can.Model.extend({
 id: "Id"
},{});
```
