#!/usr/bin/env bash

is_web_user() {
  if [ "$(whoami)" != "www-data" ] && [ "$(whoami)" != "nginx" ];
  then
    return 1
  fi
}

run_as() {
  # This function executes a command as a web user
  # If the user is already a web user, it does not 
  # switch the user.
  # Receives:
  # Command to execute as first argument
  # user to execute as second argument
  local command="$1"
  local user="${2}"

  if ! is_web_user ; then
    local shell="-s /bin/bash"
    local switch_user="su -c"

    #shellcheck disable=SC2086
    $switch_user "$command" $shell "$user"
  else
    #shellcheck disable=SC2086
    $command
  fi
}
