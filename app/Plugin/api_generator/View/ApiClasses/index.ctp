<?php
/**
 * Home Page for the Api Generator
 *
 */
?>
<div class="doc-body">
<h1>Hey you have an API now!</h1>

<p>Be sure to install the schema by running <code>cake api_index initdb</code></p>
<p>ApiGenerator provides a File and Class browser.  The file browser should be working right now! However, if you want to use the class index, and all the goodies it entails like searches and viewing source code for classes. Then you will need to generate a class index.</p>

<p>To generate your class index run <code>cake api_index update</code> from the shell and you should get an index generated!</p>

<p>Once you've got you index setup, you should probably edit this page.  Do so by opening <code><?php echo APP; ?>plugins/api_generator/views/api_generator/index.ctp</code></p>

<p>The ApiGenerator also supports a number of configuration options.  You can setup a config file by using <code>cake api_index config</code>.</p>
</div>