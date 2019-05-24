#!/bin/bash
gitlab-runner exec docker \
--env CI_REGISTRY_IMAGE_TEST="registry.gitlab.com/passbolt/php-test-base-images" \
--env PHP_VERSION="7.2" \
--env INTERACTIVE="true" \
--docker-volumes 'composer:/root/composer/.cache/files' \
--docker-pull-policy="if-not-present" \
--timeout=0 \
local-debug
