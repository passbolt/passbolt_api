function is_valid_api_tag () {
  if [[ ! $PASSBOLT_VERSION =~ [0-9]+\.[0-9]+\.[0-9]+ ]]; then
    echo "Invalid version format: $PASSBOLT_VERSION"
    return 1
  fi
}

function is_release_candidate () {
  local version=$1
  if [[ ! $version =~ [0-9]+\.[0-9]+\.[0-9]+-rc\.[0-9]+ ]];then
    return 1
  fi
  return 0
}

function validate_config_version_and_api_tag () {
  local version_file="$1"
  local version
  version=$(echo "$PASSBOLT_VERSION" | tr -d 'v')

  if ! grep -q "$version" "$version_file"; then
    echo "Version number in version.php does not match the tag: $PASSBOLT_VERSION"
    return 1
  fi
}

