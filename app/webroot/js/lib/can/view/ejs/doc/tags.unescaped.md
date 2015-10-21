@function can.ejs.tags.unescaped <%== CODE %>
@parent can.ejs.tags 2

@signature `<%== CODE %>`

Runs JS Code and writes the _unescaped_ result into the result of the template.

The following results in "my favorite element is <B>B</B>.". Using `<%==` is useful
for sub-templates.
     
         <div>my favorite element is <%== '<B>B</B>' %>.</div>