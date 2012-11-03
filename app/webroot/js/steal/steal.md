@class steal
@parent stealjs

__steal__ is a function that loads scripts, css, and
other resources into your application.

    steal(FILE_or_FUNCTION, ...)

## Quick Walkthrough

Add a script tag that loads <code>steal/steal.js</code> and add
the path to the first file to load in the query string like:

&lt;script type='text/javascript'
    src='../steal/steal.js?myapp/myapp.js'>
&lt;/script>

Then, start loading things and using them like:

    steal('myapp/tabs.js',
          'myapp/slider.js',
          'myapp/style.css',function(){

       // tabs and slider have loaded
       $('#tabs').tabs();
       $('#slider').slider()
    })

Make sure your widgets load their dependencies too:

    // myapp/tabs.js
    steal('jquery', function(){
      $.fn.tabs = function(){
       ...
      }
    })

## Examples:

    // Loads ROOT/jquery/controller/controller.js
    steal('jquery/controller')
    steal('jquery/controller/controller.js')

    // Loads coffee script type and a coffee file relative to
    // the current file
    steal('steal/coffee').then('./mycoffee.coffee')

    // Load 2 files and dependencies in parallel and
    // callback when both have completed
    steal('jquery/controller','jquery/model', function(){
      // $.Controller and $.Model are available
    })

    // Loads a coffee script with a non-standard extension (cf)
    // relative to the current page and instructs the build
    // system to not package it (but it will still be loaded).
    steal({
       src: "./foo.cf",
       packaged: false,
       type: "coffee"
     })

The following is a longer walkthrough of how to install
and use steal:

## Adding steal to a page

After installing StealJS (or JavaScriptMVC),
find the <code>steal</code> folder with
<code>steal/steal.js</code>.

To use steal, add a script tag
to <code>steal/steal.js</code> to your
html pages.

This walkthrough assumes you have the steal script
in <code>public/steal/steal.js</code> and a directory
structure like:

@codestart text
/public
    /steal
    /pages
        myapp.html
    /myapp
        myapp.js
        jquery.js
        jquery.ui.tabs.js
@codeend

To use steal in <code>public/pages/myapp.html</code>,
add a script tag in <code>myapp.html</code>:

@codestart html
&lt;script type='text/javascript'
    src='../steal/steal.js'>
&lt;/script>
@codeend

<div class='whisper'>PRO TIP: Bottom load your scripts. It
will increase your application's percieved response time.</div>

## Loading the First Script

Once steal has been added to your page, it's time
to load scripts. We want to load <code>myapp.js</code>
and have it load <code>jquery.js</code> and
<code>jquery.ui.tabs.js</code>.

By default, steal likes your scripts
to be within in the [steal.static.root steal.root] folder.  The [steal.root] the
folder contains the <code>steal</code> folder.  In this example,
it is the <code>public</code> folder.

To load <code>myapp/myapp.js</code>, we have two options:

#### Add a script tag

Add a script tag after the steal
script that 'steals' <code>myapp.js</code> like:

@codestart html
&lt;script type='text/javascript'>
  steal('myapp/myapp.js')
&lt;/script>
@codeend

#### Add the script parameter

The most common (and shortest) way to load <code>myapp.js</code>
is to add the script path to the steal script's src after in the
query params.  So, instead of adding a script, we change
the steal script from:

@codestart html
&lt;script type='text/javascript'
    src='../steal/steal.js'>
&lt;/script>
@codeend

To

@codestart html
&lt;script type='text/javascript'
    src='../steal/steal.js?<b>myapp/myapp.js</b>'>
&lt;/script>
@codeend

<div class='whisper'>PRO TIP: You can also just add
<code>?myapp</code> to the query string.</div>

## Loading Scripts

We want to load <code>jquery.js</code> and
<code>jquery.ui.tabs.js</code> into the page and then
add then create a tabs widget.  First we need to load
<code>jquery.js</code>.

By default, steal loads script relative to [steal.root]. To
load <code>myapp/jquery.js</code> we can the following to
<code>myapp.js</code>:

    steal('myapp/jquery.js');

But, we can also load relative to <code>myapp.js</code> like:

    steal('./jquery.js');

Next, we need to load <code>jquery.ui.tabs.js</code>.  You
might expect something like:

    steal('./jquery.js','./jquery.ui.tabs.js')

to work.  But there are two problems / complications:

  - steal loads scripts in parallel and runs out of order
  - <code>jquery.ui.tabs.js</code> depends on jQuery being loaded

This means that steal might load <code>jquery.ui.tabs.js</code>
before <code>jquery.js</code>.  But this is easily fixed.

[steal.static.then] waits until all previous scripts have loaded and
run before loading scripts after it.  We can load <code>jquery.ui.tabs.js</code>
after <code>jquery.js</code> like:

    steal('./jquery.js').then('./jquery.ui.tabs.js')

Finally, we need to add tabs to the page after
the tabs's widget has loaded.  We can add a callback function to
steal that will get called when all previous scripts have finished
loading:

    steal('./jquery.js').then('./jquery.ui.tabs.js', function($){
      $('#tabs').tabs();
    })

## Other Info

### Exclude Code Blocks From Production

To exclude code blocks from being included in
production builds, add the following around
the code blocks.

    //!steal-remove-start
        code to be removed at build
    //!steal-remove-end

### Lookup Paths

By default steal loads resources relative
to [steal.static.root steal.root].  For example, the following
loads foo.js in <code>steal.root</code>:

    steal('foo.js'); // loads //foo.js

This is the same as writing:

    steal('//foo.js');

Steal uses <code>'//'</code> to designate the [steal.static.root steal.root]
folder.

To load relative to the current file, add <code>"./"</code> or
 <code>"../"</code>:

    steal("./bar.js","../folder/zed.js");

Often, scripts can be found in a folder within the same
name. For example, [jQuery.Controller $.Controller] is
in <code>//jquery/controller/controller.js</code>. For convience,
if steal is provided a path without an extension like:

    steal('FOLDER/PLUGIN');

It is the same as writing:

    steal('FOLDER/PLUGIN/PLUGIN.js')

This means that <code>//jquery/controller/controller.js</code>
can be loaded like:

     steal('jquery/controller')

### Types

steal can load resources other than JavaScript.


@constructor

Loads resources specified by each argument.  By default, resources
are loaded in parallel and run in any order.


@param {String|Function|Object} resource...

Each argument specifies a resource.  Resources can
be given as a:

### Object

An object that specifies the loading and build
behavior of a resource.

     steal({
       src: "myfile.cf",
       type: "coffee",
       packaged: true,
       unique: true,
       ignore: false,
       waits: false
     })

The available options are:

 - __src__ {*String*} - the path to the resource.

 - __waits__ {*Boolean default=false*} - true the resource should wait
   for prior steals to load and run. False if the resource should load and run in
   parallel.  This defaults to true for functions.

 - __unique__ {*Boolean default=true*} - true if this is a unique resource
   that 'owns' this url.  This is true for files, false for functions.

 - __ignore__ {*Boolean default=false*} - true if this resource should
   not be built into a production file and not loaded in
   production.  This is great for script that should only be available
   in development mode.  This script is loaded during compression, but not
   added to the bundled script.

 - __packaged__ {*Boolean default=true*} - true if the script should be built
   into the production file. false if the script should not be built
   into the production file, but still loaded.  This is useful for
   loading 'packages'.  This script is loaded during compression, but not
   added to the bundled script.  The difference with ignore is packaged still
   steals this file while production mode, from an external script.

 - __type__ {*String default="js"*} - the type of the resource.  This
   is typically inferred from the src.

### __String__

Specifies src of the resource.  For example:

      steal('./file.js')

Is the same as calling:

      steal({src: './file.js'})

### __Function__

A callback function that runs when all previous steals
have completed.

    steal('jquery', 'foo',function(){
      // jquery and foo have finished loading
      // and running
    })

@return {steal} the steal object for chaining
