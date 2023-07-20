# This function parses a tag in the form of:
# v3.11.0-rc.1-pro-all
#
# All of the fields are mandatory:
# Version: v3.11.0-rc.1|v3.11.0
# Passbolt flavour: pro|ce
# Per package filter: all|rpm|debian
#
# It also provides the component based on if it is RC: testing|stable
function parse_tag() {
  local tag=$1

  if is_release_candidate "$tag"; then
    echo "$tag" | awk -F '-' '{print $1"-"$2,$3,$4,"testing"}'
  else
    echo "$tag" | awk -F '-' '{print $1,$2,$3,"stable"}'
  fi
}

# Example [branch: develop] # points to pro flavour
# Example [flavour: ce] # points to release branch
# Example [branch: develop, flavour: ce]
#
# If the commit message does not contain any of the above patterns
# We default to clone: pro api release branch
function parse_commit_message() {
  local message="$1"
  local branch
  local flavour
  local component
  local filter

  branch=$(calculate_regex "$message" "release" "branch")
  component=$(calculate_regex "$message" "testing" "component")
  filter=$(calculate_regex "$message" "all" "filter")
  flavour=$(calculate_regex "$message" "pro" "flavour")

  echo "$branch" "$flavour" "$filter" "$component"
}

function calculate_regex() {
  local message="$1"
  local default_value="$2"
  local pattern="$3"

  result="$(echo "$message" | sed -nE "s/.*\[.*($pattern: *)([^]|^ |^,]+).*\]/\\2/p")"
  echo "${result:-$default_value}"
}
