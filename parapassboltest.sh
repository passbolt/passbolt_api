#!/bin/bash

set -euo pipefail

# PARAPASSBOLTTEST AKA PARAPA
#
# THIS SCRIPT IS EXPERIMENTAL
# Adjust test size to your CPU CORES.
# By default PHP version is 8
# You need access to the gitlab registry of this project: passbolt/php-test-base-images
# Around 200 or 300 in 8 cpu machine takes 1 minute to run all tests. Other combinations could lead to broken tests ??
# TODO run tests isolated 1 by 1
# Test logs are stored in logfiles on the current working dir and showed to stdout at
# the end of the execution
# You can follow the execution with: watch -n1 docker ps
#
# If containers remain dangling after a script execution you can run manually:
#  docker ps -a | grep mysqltestLAB | awk '{print $1}' | xargs docker kill
#  docker ps -a | grep mysqltestLAB | awk '{print $1}' | xargs docker rm
#  docker ps -a | grep phpunitLAB | awk '{print $1}' | xargs docker kill
#  docker ps -a | grep phpunitLAB | awk '{print $1}' | xargs docker rm

TEST_GROUP_SIZE=200
MYSQL_VERSION=5.7
PHP_VERSION=8.1
PIDS=()

cleanup_docker() {
  while docker ps | grep phpunitLAB; do sleep 1; done
  docker ps -a | grep mysqltestLAB | awk '{print $1}' | xargs docker kill
  docker ps -a | grep mysqltestLAB | awk '{print $1}' | xargs docker rm
  docker ps -a | grep phpunitLAB | awk '{print $1}' | xargs docker rm

  docker kill mysqltestcounter
  docker rm mysqltestcounter
}

calculate_test_groups() {

  # Run a mysql container required to run phpunit. Even for running --list-tests??
  docker run -d --name mysqltestcounter --tmpfs /var/lib/mysql:rw -e MYSQL_USER=test -e MYSQL_PASSWORD=test -e MYSQL_DATABASE=test -e MYSQL_ROOT_PASSWORD=test mysql:$MYSQL_VERSION
  local DB_HOST=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mysqltestcounter)

  # Get all tests and split them in regex groups
  docker run -i --rm --workdir='/app' -v ${PWD}:/app registry.gitlab.com/passbolt/php-test-base-images:$PHP_VERSION /bin/bash -c "
      until mysqladmin -h $DB_HOST -u root -ptest ping > /dev/null 2>&1; do
        sleep 1
      done && \
      gpg --import config/gpg/unsecure_private.key > /dev/null 2>&1 && \
      gpg --import config/gpg/unsecure.key > /dev/null 2>&1 && \
      PASSBOLT_REGISTRATION_PUBLIC=1 \
      PASSBOLT_SELENIUM_ACTIVE=1 \
      APP_FULL_BASE_URL=http://127.0.0.1 \
      PASSBOLT_GPG_SERVER_KEY_PUBLIC=config/gpg/unsecure.key \
      PASSBOLT_GPG_SERVER_KEY_PRIVATE=config/gpg/unsecure_private.key \
      PASSBOLT_GPG_SERVER_KEY_FINGERPRINT=2FC8945833C51946E937F9FED47B0811573EE67E \
      DATASOURCES_TEST_DATABASE=test \
      DATASOURCES_TEST_USERNAME=test \
      DATASOURCES_TEST_PASSWORD=test \
      DATASOURCES_TEST_HOST=$DB_HOST \
      vendor/bin/phpunit --list-tests" | \
    awk -F '::' '{print $2}' | \
    awk NF | \
    awk -F '\"' '{print $1}' | \
    awk -F '#' '{print $1}' | \
    uniq | \
    awk '{print $1".*|"}' | \
    awk -v n=$TEST_GROUP_SIZE '1; NR % n == 0 {print ""}' | \
    awk '{ORS = sub(/\|$/,"|") ? "" : "\n"} 1' | \
    awk '{sub(/\|$/,"")}1' | \
    awk '{print "/"$1"/"}'
}

start_test() {

  local SERVER=$1
  local TEST_GROUP=$2
  local MYSQL_IP=1.1.1.1
  docker run -d --name mysqltestLAB$SERVER --tmpfs /var/lib/mysql:rw -e MYSQL_USER=test -e MYSQL_PASSWORD=test -e MYSQL_DATABASE=test -e MYSQL_ROOT_PASSWORD=test mysql:$MYSQL_VERSION
  MYSQL_IP=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mysqltestLAB$SERVER)

	RANDOM_DB_NAME=$(echo $RANDOM)
  docker run -w=/app --name phpunitLAB$SERVER -v ${PWD}:/app registry.gitlab.com/passbolt/php-test-base-images:$PHP_VERSION bash -c "
      until mysqladmin -h $MYSQL_IP -u root -ptest ping > /dev/null 2>&1; do
        sleep 1
      done
      mysqladmin -u root -h ${MYSQL_IP} create test$RANDOM_DB_NAME -ptest
      gpg --import config/gpg/unsecure_private.key > /dev/null 2>&1 && \
      gpg --import config/gpg/unsecure.key > /dev/null 2>&1 && \
      PASSBOLT_REGISTRATION_PUBLIC=1 \
      PASSBOLT_SELENIUM_ACTIVE=1 \
      APP_FULL_BASE_URL=http://127.0.0.1 \
      PASSBOLT_GPG_SERVER_KEY_PUBLIC=config/gpg/unsecure.key \
      PASSBOLT_GPG_SERVER_KEY_PRIVATE=config/gpg/unsecure_private.key \
      PASSBOLT_GPG_SERVER_KEY_FINGERPRINT=2FC8945833C51946E937F9FED47B0811573EE67E \
      DATASOURCES_TEST_DATABASE=test \
      DATASOURCES_TEST_USERNAME=test \
      DATASOURCES_TEST_PASSWORD=test \
      DATASOURCES_TEST_HOST=${MYSQL_IP} \
      vendor/bin/phpunit --filter='${TEST_GROUP}'" > phpunit_test_log$SERVER.txt &

  until docker inspect -f '{{.State.Pid}}' phpunitLAB$SERVER; do
    sleep 1
  done

  PIDS+=$(docker inspect -f '{{.State.Pid}}' phpunitLAB$SERVER)
}

# Start the tests in background
COUNT=0
for group in $(calculate_test_groups); do
  start_test $COUNT $group &
  COUNT=$(($COUNT +1))
done

# Hacky way to make the script wait till threads are done
sleep 70
for pid in ${PIDS[@]}; do
  wait $pid
done

# Clean docker containers
cleanup_docker

# Show test results in console
cat phpunit_test_log*.txt
