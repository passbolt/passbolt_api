@page funcunit.maven Maven
@parent funcunit.integrations 4

[http://maven.apache.org/ Maven] is a build tool, usually used for Java projects. FuncUnit can be 
run from maven as part of the build, using the 
[http://maven.apache.org/plugins/maven-antrun-plugin/ maven antrun plugin].

@codestart xml
 &lt;plugin>
   &lt;artifactId>maven-antrun-plugin&lt;/artifactId>
   &lt;version>1.6&lt;/version>
   &lt;executions>
     &lt;execution>
       &lt;id>funcunit&lt;/id>
       &lt;phase>test&lt;/phase>
       &lt;configuration>
         &lt;tasks>
            &lt;echo>Running funcunit tests&lt;/echo>
            &lt;exec dir="src/main/webapp/ui" executable="src/main/webapp/ui/js"
                  resolveexecutable="true" failonerror="true">
              &lt;arg value="-e" />
              &lt;arg value="funcunit/run" />
              &lt;arg value="phantomjs" />
              &lt;arg value="funcunit.html" />
            &lt;/exec>
         &lt;/tasks>
       &lt;/configuration>
       &lt;goals>
         &lt;goal>run&lt;/goal>
       &lt;/goals>
     &lt;/execution>
   &lt;/executions>
 &lt;/plugin>
@codeend

## Failing on errors

To force maven to fail the build on errors, FuncUnit has to exit "hard" with exit code 1 if there's a 
failure.

1. Run the js script with the "-e" flag as shown above. This tells the batch script to exit 
hard if something throws an error.
1. Set the failonerror="true" flag in the maven antrun plugin xml, as shown above.
1. Set the failOnError: true property in settings.js and use the xunit reporter (this is the default). If this 
property is set, xunit will exit with exit code 1 if there are failed tests.

 