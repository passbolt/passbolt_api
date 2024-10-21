
#!/bin/bash

# This script receives an passbolt tag and returns the passbolt version semver compliant (without 'v')
# It can handle stable versions eg. v3.11.0 -> 3.11.0
# and release candiate versions eg. v3.11.0-rc.1 -> 3.11.0-rc.1

tag="$1"

function is_release_candidate () {
  local version=$1
  if [[ ! $version =~ [0-9]+\.[0-9]+\.[0-9]+-rc\.[0-9]+ ]];then
    return 1
  fi
  return 0
}

function is_testing_candidate () {
  local version=$1
  if [[ ! $version =~ [0-9]+\.[0-9]+\.[0-9]+-test\.[0-9]+ ]];then
    return 1
  fi
  return 0
}

function is_stable_candidate () {
  local version=$1
  if [[ ! $version =~ [0-9]+\.[0-9]+\.[0-9]+$ ]];then
    return 1
  fi
  return 0
}

function parse_tag() {
  local tag=$1

  if is_testing_candidate "$tag"; then
    echo "$tag" | awk -F '-' '{print $1"-"$2}' | tr -d 'v'
  fi

  if is_release_candidate "$tag"; then
    echo "$tag" | awk -F '-' '{print $1"-"$2}' | tr -d 'v'
  fi

  if is_stable_candidate "$tag"; then
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
export PB_VERSION=${version}
