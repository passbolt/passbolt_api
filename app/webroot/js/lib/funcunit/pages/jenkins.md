@page funcunit.jenkins Jenkins
@parent funcunit.integrations 3

[http://jenkins-ci.org/ Jenkins] is a continuous integration tool. It is used to run builds and 
continuously check the health of the project.  If a test fails or the build breaks, it sends 
emails to the dev team to immediately alert everyone of regressions.

THe [https://wiki.jenkins-ci.org/display/JENKINS/xUnit+Plugin XUnit plugin] allows Jenkins to tie 
into testing tools. The tool just has to create an XML output file in the standard 
[http://xunit.codeplex.com/wikipage?title=XmlFormat XUnit format].  Jenkins will fail the build 
if the file shows errors.

## Install

1. Install Jenkins
1. Install the XUnit plugin
1. Uncomment the outputFile in funcunit/settings.js (which will cause the testresults.xml file to be created)
1. Create a build
1. Add the something similar to the following to a shell build step.  This script will run your 
funcunit tests and copy the testresults.xml file into where jenkins wants it

@codestart
cd path/to/your/project
./js funcunit/run phantomjs path/to/funcunit.html
cp testresults.xml /Users/bmoschel/.jenkins/jobs/JMVC/workspace/
@codeend

5. Click publish testing tools report and point XUnit to where to find this file
6. Run a new build