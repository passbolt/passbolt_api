#!/usr/bin/env bash

regular_user_warning() {
  cat << EOF 
  This script require to be executed as the same user as your web server is using.
  Typically this users are www-data or nginx.
  You can switch your user to the web user before using this script or execute it as root.

  Examples:
    - Execute as root (this option will switch automatically to www-data):

      $ sudo $0

    - Execute as root, switching to nginx user:

      $ sudo $0 nginx

    - Switch to web user:

      $ sudo su www-data
      $ $0
EOF
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

  case $(whoami) in

    root)
      local shell="-s /bin/bash"
      local switch_user="su -c"

      #shellcheck disable=SC2086
      $switch_user "$command" $shell "$user"
      ;;

    www-data | nginx | wwwrun)
      #shellcheck disable=SC2086
      $command
      ;;

    *)
      regular_user_warning
      exit 1
      ;;

  esac

}

oops() {
  local red='\033[0;31m'
  local nc='\033[0m' # No Color
  local err
  err=$("$@" 2>&1) && echo "$err" || echo -e "$red ERROR: $nc$err"
}

get_web_user() {
  if [ -f /etc/debian_version ]
  then
    echo "www-data"
  elif [ -f /usr/bin/zypper ]
  then
    echo "wwwrun"
  else
    echo "nginx"
  fi
}