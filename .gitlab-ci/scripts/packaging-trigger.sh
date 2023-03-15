#!/bin/bash

# This script receives an passbolt tag and returns the passbolt version semver compliant (without 'v')
# It can handle stable versions eg. v3.11.0 -> 3.11.0
# and release candiate versions eg. v3.11.0-rc.1 -> 3.11.0-rc.1

tag="$1"
branch="$2"

function is_release_candidate () {
  local version=$1
  if [[ ! $version =~ [0-9]+\.[0-9]+\.[0-9]+-rc\.[0-9]+ ]];then
    return 1
  fi
  return 0
}

function parse_tag() {
  local tag=$1

  if is_release_candidate "$tag"; then
    echo "$tag" | awk -F '-' '{print $1"-"$2}' | tr -d 'v'
  else
    echo "$tag" | awk -F '-' '{print $1}' | tr -d 'v'
  fi
}

if [[ $tag == "" ]]; then
  echo "Error: tag is empty!"
  exit 1 
else
  version="$(parse_tag "$tag")"
fi

echo "Creating the following variables"
echo "================================="
echo "PASSBOLT_VERSION=${version}"

passbolt_version="${version}"

curl -X POST \
     -F token="$PACKAGING_TOKEN" \
     -F "ref=$branch" \
     -F "variables[PASSBOLT_FLAVOUR]=$PASSBOLT_FLAVOUR" \
     -F "variables[PASSBOLT_VERSION]=$passbolt_version" \
     -F "variables[PASSBOLT_BRANCH]=$tag" \
     "https://gitlab.com/api/v4/projects/$DOWNSTREAM_PROJECT_ID/trigger/pipeline"
