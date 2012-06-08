TOC:
  A.  How to get (and contribute) to JMVC 3.2 
  B.  Getting Started with JMVC 3.2


A.  How to get (and contribute) JMVC

  1.  Start a new project in git.
  
  2.  Fork ....
           http://github.com/jupiterjs/steal     and 
           http://github.com/jupiterjs/jquerymx  and 
           http://github.com/jupiterjs/funcunit  and 
           http://github.com/jupiterjs/documentjs
  
  3.  Add steal, jquerymx, funcunit, and documentjs as submodules of your project...
           git submodule add git@github.com:_YOU_/steal.git steal
           git submodule add git@github.com:_YOU_/jquerymx.git jquery
           git submodule add git@github.com:_YOU_/funcunit.git funcunit
           git submodule add git@github.com:_YOU_/documentjs.git documentjs
  
  4.  Learn a little more about submodules ...
           http://johnleach.co.uk/words/archives/2008/10/12/323/git-submodules-in-n-easy-steps
           
  5.  Make changes in steal or jmvc, and push them back to your fork.
  
  6.  Make a pull request to your fork.
 
  
B. Getting Started with JMVC

  1. Generate app: 
  			./js steal/generate/app cookbook

  2. Generate scaffold:
  			./js steal/generate/scaffold Cookbook.Models.Recipe

  3. Add html to cookbook/cookbook.html:
			<ul id='recipes'></ul>
			<form id='create' action=''></form>

  4. See your recipes app:  
  			Open cookbook/cookbook.html in a browser.

  5. Run functional tests in the browser: 
  			Open cookbook/funcunit.html in a browser (turn off popup blockers).

  6. Run functional tests with selenium:
  			./funcunit/run selenium cookbook/funcunit.html

  7. Run unit tests in the browser: 
  			Open cookbook/qunit.html in a browser.

  8. Run unit tests with Rhino:
  			./funcunit/run envjs cookbook/qunit.html

  11. Compress app:
  			./steal/js cookbook/scripts/build.js

  12. Turn on production mode in callcenter.html and reload page:
			src='../steal/steal.production.js?cookbook'

  13. Generate docs:
  			./steal/js cookbook/scripts/doc.js

  14. View docs: 
			Open cookbook/docs.html.
