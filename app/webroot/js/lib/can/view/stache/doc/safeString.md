@function can.stache.safeString
@parent can.stache.static

@signature `can.stache.safeString(str)`

@param {String} str A string you don't want to become escaped.
@return {String} A string flagged by `can.mustache` as safe, which will
not become escaped, even if you use [can.stache.tags.unescaped](triple slash).

@body

If you write a helper that generates its own HTML, you will
usually want to return a `can.stache.safeString.` In this case,
you will want to manually escape parameters with `[can.esc].`


    can.stache.registerHelper('link', function(text, url) {
      text = can.esc(text);
      url  = can.esc(url);
    
      var result = '<a href="' + url + '">' + text + '</a>';
      return can.mustache.safeString(result);
    });


Rendering:

```
<div>{{link "Google", "http://google.com"}}</div>
```

Results in:

```
<div><a href="http://google.com">Google</a></div>
```

As an anchor tag whereas if we would have just returned the result rather than a
`can.stache.safeString` our template would have rendered a div with the escaped anchor tag.

