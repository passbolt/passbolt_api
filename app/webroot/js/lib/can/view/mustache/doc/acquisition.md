@page can.mustache.Acquisition Template Acquisition
@parent can.mustache.pages 4

There are number of ways to acquire templates such as: raw text,
URL, or script tags in the markup.

__Raw Text__

Raw text can be templated by passing an object with a `text`
attribute containing your template and Mustache will return a 
document fragment back.  For example:

	var template = "My body lies over the {{.}}";
	var fragment = new can.mustache({ text: template })
					.render('water');
	can.append(can.$(document.body), can.view.frag(fragment));

__Script Tags__

Inline script tags in your HTML document can be used to render 
templates.  Set the `type` to `text/mustache` and the `id` as a unique
key Mustache will use for look up.

	<script id="mytemplate" type="text/mustache">
		My body lies over the {{.}}
	</script>

	var template = can.view("#mytemplate", 'water');
	can.$(document.body).append(template);

__URL__

Templates can be defined in their own files and  Mustache will fetch the
files on render.  This is the preferred way since it will keep your application
nicely organized seperating views from logic code. 

	var template = can.view('//lib/views/mytemplate.mustache', 
					dataToPass);
	can.$(document.body).append(template);

Since this could potentially make several XHR requests, in a big application
this could be a performance concern.  Creating a build step to 
concatenate and include all of the views in one file would be one way to optimize performance.
If you are using <a href="http://javascriptmvc.com/docs/#!stealjs">Steal</a>, it will do this automatically at build for you.