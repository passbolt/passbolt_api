# How to contribute

Passbolt would love to welcome your contributions. There are several ways to help out:
* Create an [issue](https://github.com/cakephp/cakephp/issues) on GitHub, if you have found a bug
* Write test cases for open bug issues
* Write patches for open bug/feature issues, preferably with test cases included
* Contribute to the [documentation](https://passbolt.com/help)

There are a few guidelines that we need contributors to follow so that we have a
chance of keeping on top of things.

## Code of Conduct

Help us keep Passbolt open and inclusive.
Please read and follow our [Code of Conduct](https://www.passbolt.com/code_of_conduct).

## Getting Started

* Make sure you have a [GitHub account](https://github.com/signup/free).
* If you are planning to start a new functionality or create a major change request, write down the functional and technical specifications first.
  * Create a document that is viewable by everyone
  * Define the problem you are trying to solve, who is impacted, why it is important, etc.
  * Present a solution. Explaining your approach gives an opportunity for other people to contribute and avoid frictions down the line.
* Submit an [issue](https://github.com/passbolt/passbolt/issues)
  * Check first that a similar issue does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug and/or a link to the specification document
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub.

## Making Changes

* Create a feature branch from where you want to base your work.
  * This is usually the master branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a feature branch based on master; `git branch
    feature/ID_feature_description master` then checkout the new branch with `git
    checkout feature/ID_feature_description`. Better avoid working directly on the
    `master` branch, to avoid conflicts if you pull in updates from origin.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Use descriptive commit messages and reference the #issue number.
* PHP unit test cases should continue to pass. You can run tests locally or enable [travis-ci](https://travis-ci.org/)
for your fork, so all tests and codesniffs will be executed.
* Selenium tests should continue to pass. See [passbolt selenium test suite](https://github.com/passbolt/passbolt_selenium)
* Your work should apply the [CakePHP coding standards](http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html).

## Which branch to base the work

* Bugfix branches will be based on master.
* New features that are backwards compatible will be based on next minor release branch.
* New features or other non backwards compatible changes will go in the next major release branch.

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the official passbolt repository, with the correct target branch.

## Test cases and codesniffer

Please checkout the README file to learn how to run the test suites and check the code standards.

## Reporting a Security Issue

If you've found a security related issue in Passbolt, please don't open an issue in GitHub.
Instead contact us at contact@passbolt.com. In the spirit of responsible disclosure we ask that the reporter keep the
issue confidential until we announce it.

The passbolt team will take the following actions:
- Try to first reproduce the issue and confirm the vulnerability.
- Acknowledge to the reporter that we’ve received the issue and are working on a fix.
- Get a fix/patch prepared and create associated automated tests.
- Prepare a post describing the vulnerability, and the possible exploits.
- Release new versions of all affected major versions.
- Prominently feature the problem in the release announcement.
- Provide credits in the release announcement to the reporter if they so desire.

# Additional Resources

* [Existing issues](https://github.com/passbolt/passbolt/issues)
* [Development Roadmaps](https://www.passbolt.com/roadmap)
* [General GitHub documentation](https://help.github.com/)
* [GitHub pull request documentation](https://help.github.com/send-pull-requests/)
