#!/bin/bash

set -euo pipefail

# PARAPASSBOLTTEST AKA PARAPA (I'm loving it, yes, sorry about the reference)
#
# THIS SCRIPT IS EXPERIMENTAL
# Adjust test size to your CPU CORES.
# By default PHP version is 8
# Test logs are stored in logfiles on the $LOG_PATH and showed to stdout at
# the end of the execution
#

NUMBER_OF_WORKERS=13
DATABASE_DOCKER_IMAGE="mysql:5.7"
PHP_UNIT_DOCKER_IMAGE="registry.gitlab.com/passbolt/php-test-base-images:8.1"
PHPUNIT_XML_FILE="tmp/paratest/phpunit.xml"
PIDS=()
LOG_PATH="tmp/paratest"
PROGNAME="$0"

usage() {

  cat <<-EOF
  usage: $PROGNAME [OPTION] [ARGUMENTS]

  Always use this script from the root of the passbolt directory

  OPTIONS:

   -h this help message
   -d Relational database docker image (default mysql:5.7, intel)
   -p PHP base docker image (default php 8.1 image)
   -w number of workers that execute the test in parallel

  EXAMPLES:

   Default (only intel):
   -------------------------------------------
   bin/paratest

   Intel:
   -------------------------------------------
   bin/paratest -d "mysql:5.7" -p "registry.gitlab.com/passbolt/php-test-base-images:8.1"

   M1:
   -------------------------------------------
   bin/paratest -d "biarms/mysql" -p "registry.gitlab.com/passbolt/php-test-base-images/8.1-arm64:8.1"

EOF
}

get_options() {

  local OPTIND=$OPTIND

  while getopts "hd:p:t:" opt; do
    case $opt in
      d)
        DATABASE_DOCKER_IMAGE="$OPTARG"
        ;;
      p)
        PHP_UNIT_DOCKER_IMAGE="$OPTARG"
        ;;
      w)
        NUMBER_OF_WORKERS="$OPTARG"
        ;;
      h)
        usage
        exit 0
        ;;
      *)
        echo "Invalid option"
        exit 1
        ;;
    esac
  done
}

cleanup_docker() {

  printf "Waiting for php containers to finish"
  while docker ps | grep passboltUnitTestGroup > /dev/null 2>&1; do
    printf "."
    sleep 1
  done
  printf "DONE! \n"

  EXIT_CODES=$(docker ps -a | grep passboltUnitTestGroup | awk '{print $1}' | xargs -I {} docker inspect {} --format='{{.State.ExitCode}}')

  for code in $EXIT_CODES; do
    if [[ $code != 0 ]]; then
      EXIT_STATUS=$code
      break
    fi
  done

  docker ps -a | grep phpunitDataSource | awk '{print $1}' | xargs docker kill
  docker ps -a | grep phpunitDataSource | awk '{print $1}' | xargs docker rm
  docker ps -a | grep passboltUnitTestGroup | awk '{print $1}' | xargs docker rm
}

start_test_mysql() {

  local SERVER=$1
  local TEST_GROUP=$2
  local DATASOURCE_HOST=1.1.1.1

  docker run -d \
    --name phpunitDataSource$SERVER \
    --tmpfs /var/lib/mysql:rw \
    -e MYSQL_USER=test \
    -e MYSQL_PASSWORD=test \
    -e MYSQL_DATABASE=test \
    -e MYSQL_ROOT_PASSWORD=test \
    $DATABASE_DOCKER_IMAGE \
    bash -c "
      docker-entrypoint.sh mysqld \
        --default-authentication-plugin=mysql_native_password \
        --character-set-server=utf8mb4 \
        --collation-server=utf8mb4_unicode_ci \
        --log-bin-trust-function-creators=1
    "

  DATASOURCE_HOST=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' phpunitDataSource$SERVER)

	RANDOM_DB_NAME=$(echo $RANDOM)
  docker run \
    -w=/app \
    --name passboltUnitTestGroup$SERVER \
    -v ${PWD}:/app \
    $PHP_UNIT_DOCKER_IMAGE bash -c "
      until mysqladmin -h $DATASOURCE_HOST -u root -ptest ping > /dev/null 2>&1; do
        sleep 1
      done
      mysqladmin -u root -h ${DATASOURCE_HOST} create test$RANDOM_DB_NAME -ptest && \
      gpg --import config/gpg/unsecure_private.key > /dev/null 2>&1 && \
      gpg --import config/gpg/unsecure.key > /dev/null 2>&1 && \
      DEBUG=1 \
      PASSBOLT_REGISTRATION_PUBLIC=1 \
      PASSBOLT_SELENIUM_ACTIVE=1 \
      APP_FULL_BASE_URL=http://127.0.0.1 \
      PASSBOLT_GPG_SERVER_KEY_PUBLIC=config/gpg/unsecure.key \
      PASSBOLT_GPG_SERVER_KEY_PRIVATE=config/gpg/unsecure_private.key \
      PASSBOLT_GPG_SERVER_KEY_FINGERPRINT=2FC8945833C51946E937F9FED47B0811573EE67E \
      DATASOURCES_TEST_DATABASE=test \
      DATASOURCES_TEST_USERNAME=root \
      DATASOURCES_TEST_PASSWORD=test \
      DATASOURCES_TEST_HOST=${DATASOURCE_HOST} \
      vendor/bin/phpunit -c '${PHPUNIT_XML_FILE}' --testsuite '${TEST_GROUP}'" > tmp/paratest/phpunit_test_log$SERVER.txt &

  until docker inspect -f '{{.State.Pid}}' passboltUnitTestGroup$SERVER > /dev/null 2>&1; do
    sleep 1
  done

  PIDS+=$(docker inspect -f '{{.State.Pid}}' passboltUnitTestGroup$SERVER)
}

start_test_postgres() {

  local SERVER=$1
  local TEST_GROUP=$2
  local DATASOURCE_HOST=1.1.1.1

  docker run -d \
    --name phpunitDataSource$SERVER \
    --tmpfs /var/lib/postgresql/data:rw \
    -e POSTGRES_USER=test \
    -e POSTGRES_PASSWORD=test \
    -e POSTGRES_HOST_AUTH_METHOD=trust \
    -e POSTGRES_DATABASE=test \
    $DATABASE_DOCKER_IMAGE

  DATASOURCE_HOST=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' phpunitDataSource$SERVER)

	RANDOM_DB_NAME=$(echo $RANDOM)
  docker run \
    -w=/app \
    --name passboltUnitTestGroup$SERVER \
    -v ${PWD}:/app \
    $PHP_UNIT_DOCKER_IMAGE bash -c "
      until pg_isready --host=$DATASOURCE_HOST --port=5432 > /dev/null 2>&1; do
        sleep 1
      done
      psql -U test -h ${DATASOURCE_HOST} -c 'create database test$RANDOM_DB_NAME;' && \
      gpg --import config/gpg/unsecure_private.key > /dev/null 2>&1 && \
      gpg --import config/gpg/unsecure.key > /dev/null 2>&1 && \
      DEBUG=1 \
      PASSBOLT_REGISTRATION_PUBLIC=1 \
      PASSBOLT_SELENIUM_ACTIVE=1 \
      APP_FULL_BASE_URL=http://127.0.0.1 \
      PASSBOLT_GPG_SERVER_KEY_PUBLIC=config/gpg/unsecure.key \
      PASSBOLT_GPG_SERVER_KEY_PRIVATE=config/gpg/unsecure_private.key \
      PASSBOLT_GPG_SERVER_KEY_FINGERPRINT=2FC8945833C51946E937F9FED47B0811573EE67E \
      DATASOURCES_TEST_DATABASE=test \
      DATASOURCES_TEST_USERNAME=test \
      DATASOURCES_TEST_PASSWORD=test \
      DATASOURCES_TEST_DRIVER='Cake\Database\Driver\Postgres' \
      DATASOURCES_TEST_PORT=5432 \
      DATASOURCES_TEST_ENCODING=utf8 \
      DATASOURCES_TEST_HOST=${DATASOURCE_HOST} \
      vendor/bin/phpunit -c '${PHPUNIT_XML_FILE}' --testsuite '${TEST_GROUP}'" > tmp/paratest/phpunit_test_log$SERVER.txt &

  until docker inspect -f '{{.State.Pid}}' passboltUnitTestGroup$SERVER > /dev/null 2>&1; do
    sleep 1
  done

  PIDS+=$(docker inspect -f '{{.State.Pid}}' passboltUnitTestGroup$SERVER)
}

calculate_test_suites() {

  php ./tests/create_paratest_suites.php "$PHPUNIT_XML_FILE" "$NUMBER_OF_WORKERS"
}

get_options "$@"

EXIT_STATUS=0
mkdir -p $LOG_PATH
rm $LOG_PATH/* || true

calculate_test_suites

if [[ "$DATABASE_DOCKER_IMAGE" == *"mysql"* ]] || [[ "$DATABASE_DOCKER_IMAGE" == *"mariadb"* ]] ; then
  # Start the tests in background
  COUNT=0
  for (( group=1; group<=$NUMBER_OF_WORKERS; group++ )); do
    start_test_mysql $COUNT $group &
    COUNT=$(($COUNT +1))
  done
fi

if [[ "$DATABASE_DOCKER_IMAGE" == *"postgre"* ]]; then
  # Start the tests in background
  COUNT=0
  for (( group=1; group<=$NUMBER_OF_WORKERS; group++ )); do
    start_test_postgres $COUNT $group &
    COUNT=$(($COUNT +1))
  done
fi

wait
# Clean docker containers
cleanup_docker

# Show test results in console
cat $LOG_PATH/phpunit_test_log*.txt

exit $EXIT_STATUS
