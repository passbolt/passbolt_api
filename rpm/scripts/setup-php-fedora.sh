#!/bin/sh

# https://rpms.remirepo.net/wizard/

set -eu

OS_VERSION=$(grep -E '^VERSION_ID=' /etc/os-release | awk -F= '{print $2}' | sed 's/\"//g')
OS_VERSION_MAJOR=$(echo ${OS_VERSION:0:1} | bc)

if ! rpm -qa | grep -q remi-release
then
  dnf install -y https://rpms.remirepo.net/fedora/remi-release-${OS_VERSION}.rpm
fi

dnf install -y dnf-plugins-core
dnf module reset php -y
dnf module install php:remi-7.4 -y
dnf config-manager --set-enabled remi

# pcre2 package needs to be upgraded to last version
# there is a bug with preg_match() if we keep the current one installed
dnf clean all
dnf upgrade -y pcre2