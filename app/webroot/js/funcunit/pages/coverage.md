@page funcunit.coverage Code Coverage
@parent FuncUnit 6

This guide will show you how to determine the code coverage of your FuncUnit tests.  Code 
coverage is tracked and calculated by [steal.instrument].

## Basic reporting

To turn on code coverage for any funcunit or qunit test, open the page with URL param steal[instrument]=true.  
For example, to run the FuncUnit test suite with instrumentation turned on, open 
[http://javascriptmvc.com/funcunit/funcunit.html?steal[instrument]=true].

After the test completes, a coverage report will be shown:

@image jmvc/images/coverage_report.png


steal[instrument]=true tells steal.instrument not to ignore any files.  Passing !jmvc will cause all JMVC and test 
directories to be ignored (which is the behavior you want for testing an application).

To see which blocks of a file are covered and which are not, click a file's name:

@image jmvc/images/coverage_file.png


The numbers on the left are how many times each line has been run.

## Running from commandline

To turn on coverage from the commandline, use the -coverage flag, like:

./js funcunit/run selenium http://javascriptmvc.com/funcunit/funcunit.html -coverage

A report will appear on the console after the test completes:

@image jmvc/images/coverage_commandline.png


The results are saved in a JSON file.  To view the report later, open funcunit/coverage/coverage.html.

## Ignoring files

When you're determining code coverage, you won't want to include non application code in your coverage statistics.  JMVC and other 
third party code will skew coverage statistics.

To ignore files when running from commandline, open funcunit/settings.js and edit the coverageIgnore property.  Any string in this 
array will be matched against files.  Matches will be ignored.  * is used as an asterisk.  *__test.js will ignore any file ending in 
__test.js.

Running in browser, you can add files to ignore via a list of patterns instrument URL param, like:

[http://javascriptmvc.com/funcunit/funcunit.html?steal[instrument]=jquery,*_test.js] 

## Cobertura-Jenkins integration

Coming soon