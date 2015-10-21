@page can.stache.Acquisition Template Acquisition
@parent can.stache.pages 4

There are number of ways to acquire templates such as: raw text,
URL, or script tags in the markup.

__Raw Text__

Raw text can be templated by passing the text containing your template.  For example:

	var text = "My body lies over the {{.}}",
		template = can.stache(text),
		fragment = template("ocean");
	
	document.body.appendChild(fragment);

__Script Tags__

Inline script tags in your HTML document can be used to render 
templates.  Set the `type` to `text/stache` and the `id` as a unique
key [can.view] will use for look up.

	<script id="mytemplate" type="text/stache">
		My body lies over the {{.}}
	</script>

	var fragment = can.view("#mytemplate", 'water');
	document.body.appendChild(fragment);

__URL__

Templates can be defined in their own files and  [can.view] will fetch the
files on render.

	var fragment = can.view('//lib/views/mytemplate.stache', 
					dataToPass);
	document.body.appendChild(fragment);

Since this could potentially make several XHR requests, in a big application
this could be a performance concern.  Creating a build step to 
concatenate and include all of the views in one file would be one way to optimize performance.
If you are using <a href="http://javascriptmvc.com/docs/#!stealjs">Steal</a>, it will do this automatically at build for you.