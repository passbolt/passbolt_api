Jenkins
#######

Install and configure jenkins for our project needs.

Install
=======

Add the repository key to your system

  sudo wget -q http://pkg.jenkins-ci.org/debian/jenkins-ci.org.key -Onano /etc/apt/sources.list

Add the repository to your sources list (/etc/apt/sources.list)

  deb http://pkg.jenkins-ci.org/debian binary/

Update your system

  sudo apt-get update

Install jenkins

  sudo apt-get install jenkins

Reach your application http://my_domain:8080

Configure
=========

Jenkins over ssl
----------------

Use apache as proxy

  a2enmod proxy
  a2enmod proxy_http
  SSL certificate

make jenkins reachable with the prefix /jenkins Add this to the jenkins launcher command line

  --prefix=/jenkins in the command line

Configure apache to redirect to jenkins

  ProxyPass         /jenkins  http://localhost:8080/jenkins
  ProxyPassReverse  /jenkins  http://localhost:8080/jenkins
  ProxyPassReverse  /jenkins  http://95.142.173.61/jenkins
  Header edit Location ^http://95.142.173.61/jenkins https://95.142.173.61/jenkins
  ProxyRequests     Off
  ProxyPreserveHost On

  # Local reverse proxy authorization override
  # Most unix distribution deny proxy by default (ie /etc/apache2/mods-enabled/proxy.conf in Ubuntu)
  <Proxy http://localhost:8080>
          Order deny,allow
          Allow from all
  </Proxy>

Configure Build
===============

Github
------

Add the git plugin (https://wiki.jenkins-ci.org/display/JENKINS/Git+Plugin)

Add your public key to your github account (https://help.github.com/articles/generating-ssh-keys)
If you encounter any trouble check this article (https://help.github.com/articles/error-permission-denied-publickey)

In your job configuration, in the source code management subsection enable git and enter the following url ssh://git@github.com/stripthis/passbolt

build your project, the source code will be copied in /my_jenkins_path/jobs/my_project_name/workspace


Create the build file
---------------------

Go to your project configuration, subsection build and add a new step

Choose the type : invoke ant
Add a target : main
Add a build file : jenkins/build.xml (the build is in the repository)
Javascript unit tests

Install phantom
---------------

Download phantom (http://code.google.com/p/phantomjs/downloads/list)

  wget http://phantomjs.googlecode.com/files/phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2

Move phantom to the convenient applications place
  sudo mv phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2 /usr/local/share

Uncompress it

  sudo tar -xjvf phantomjs-1.6.0-linux-x86_64-dynamic.tar.bz2

Make it accessible frome everywhere

  sudo ln -s /usr/local/share/phantomjs-1.6.0-linux-x86_64-dynamic/bin/phantomjs /usr/local/bin/

Write the part dedicated to the javascript jsunit (jenkins/build.xml)

See the section Display unit tests results to see how to display unit tests results (This part will be common to javascript and PHP)

PHP unit tests
--------------

Install xdebug

  sudo apt-get install php5-xdebug

Install PHPUnit

  sudo apt-get install php-pear
  sudo pear config-set auto_discover 1
  sudo pear upgrade PEAR
  sudo pear install pear.phpunit.de/PHPUnit

Write the configuration of phpunit (jenkins/phpunit.xml). This configuration file will
allow us to filter the files phpunit has to work with. And so we can exlude files from
cakephp, vendors ...

Write the part dedicated to the PHP phpunit (jenkins/build.xml)

See the section Display unit tests results to see how to display unit tests results
(This part will be common to javascript and PHP) and the section Display PHP code coverage
results to see how to display code coverage results


Display unit tests results (PHP & Javascript)
---------------------------------------------

Install xUnit Plugin (https://wiki.jenkins-ci.org/display/JENKINS/xUnit+Plugin)

Go to your project configuration, subsection Post-build Actions and add a new step

Add a Publish xUnit test result report

Add a jUnit pattern with the following pattern build/*unit/*.xml

You will see in the project page a chart which display result of your unit tests


Display PHP code coverage results
---------------------------------

Install Clover PHP Plugin (http://wiki.jenkins-ci.org/display/JENKINS/Clover+PHP+Plugin)

Go to your project configuration, subsection Post-build Actions and add a new step

Add a Publish Clover PHP Coverage Report

Add the location of the coverage xml result file build/coverage/coverage.xml

Add the location of the coverage html result build/coverage/html/


.. meta::
    :title lang=en: .. Jenkins
    :keywords lang=en: doc jenkins,continuous,integration,ci,test
