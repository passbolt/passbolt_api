# Passbolt Debian package

## Requirements

- Debian environment
- Packages: devscripts, build-essential, fakeroot, equivs, cdbs
- Vendor packages installed

### Install vendor packages


```
$ composer install --no-dev -o --prefer-dist --no-interaction
```

Install vendor packages through a container:

```
$ docker run -ti "${PWD}":/app composer install --no-dev -o --prefer-dist --ignore-platform-reqs
```

### Install build dependencies

```
sudo apt-get install devscripts build-essential fakeroot equivs cdbs
```

## Build process

```
$ sudo mk-build-deps -irt'apt-get --no-install-recommends -yV' debian/control && dpkg-checkbuilddeps
$ debuild -us -uc -b
```

The package should become available at the parent directory as passbolt-@PASSBOLT_FLAVOUR@-server_*deb.
To install it locally, use `apt-get install ./passbolt-*.deb`.

After installation, you should be able to point your browser to `http://localhost`
and follow the Web based configuration pages to get Passbolt up and running.

Removing the package is best accomplished with "apt-get purge passbolt-@PASSBOLT_FLAVOUR@-server", which will remove all the software
along with configuration files and other sensitive information. `apt-get remove` will only delete
binary files, but because passbolt is basically php code, it will not remove most files from the system.

## Testing

This package comes with a testing suite based on the automation framework kitchenci and
the infra testing framework inspec. Both tools are ruby based.

### Requirements

It is possible to run the whole testing environment from a docker container without having to install
any ruby dependency locally. For the sake of completeness we will describe the docker option and the native
installation.

#### Run test inside a docker container

##### Requirements

- Docker engine installed
- Passbolt vendors installed (See this readme section "Install vendor packages")

##### Testing execution

In order to run the tests you must mount a few paths from your host into the container:

- The docker.sock (Linux: /var/run/docker.sock)
- The docker lib dir (Linux: /var/lib/docker)
- Your user home
- /etc/passwd (To map your user to the container to avoid permission problems)
- /etc/group (Same as above)
- A directory to persist the bundle dependency installation so it does not download all the deps every time

Example run:

```
$ docker run -ti -v "${PWD}":/app \
               -u "$(id -u ${USER}):$(id -u ${USER})" \
               composer /bin/bash -c "composer global require hirak/prestissimo \
               composer install -o --prefer-dist --no-dev --ignore-platform-reqs"
$ docker run -ti -v <PATH_TO_HOST_DOCKER.SOCK>:/var/run/docker.sock \
               -v /home/<YOUR_USER>:/home/<YOUR_USER> \
               -u "$(id -u ${USER}):<THE_DOCKER_GID>" \
               -v /etc/passwd:/etc/passwd:ro \
               -v /etc/group:/etc/group:ro  \
               chef/chefdk:latest bash
```

Once in the container navigate to the code diretory. As the home folder from the host is mounted
the code should be in a directory which is known to you.

To run test in isolation (create dependencies, run tests, destroy dependencies):

```
$ cd /home/the/passbolt/repo
$ gem install bundle:2.1.2
$ kitchen list # This will show which tests and on which platforms you can run them
$ kitchen test filesystem-benchmarks-debian-buster -t tests/integration # Example test run
```

To install dependencies and run tests (without destroying the container):

```
$ cd /home/the/passbolt/repo
$ kitchen converge filesystem-benchmarks-debian-buster # This installs all the required deps to run the tests
$ kitchen verify filesystem-benchmarks-debian-buster # Runs the tests without destroying infra after
```

#### Run test from the host

##### Requirements

- ruby installed
- bundler installed

In order to run the tests:

```
$ bundle install
$ bundle exec kitchen list # This will show which tests and on which platforms you can run them
$ bundle exec kitchen test filesystem-benchmarks-debian-buster -t tests/integration # Example test run
```

Or:

```
$ bundle install
$ bundle exec kitchen converge filesystem-benchmarks-debian-buster # This installs all the required deps to run the tests
$ bundle exec kitchen verify filesystem-benchmarks-debian-buster # Runs the tests without destroying infra after
```
