#!/bin/sh

# https://rpms.remirepo.net/wizard/

set -eu

OS_VERSION=$(grep -E '^VERSION_ID=' /etc/os-release | awk -F= '{print $2}' | sed 's/\"//g')
OS_VERSION_MAJOR=$(echo ${OS_VERSION:0:1} | bc)

if [ ${OS_VERSION_MAJOR} -eq 7 ]
then
    if ! rpm -qa | grep -q epel-release
    then
      yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -y
    fi
    if ! rpm -qa | grep -q remi-release
    then
      yum install https://rpms.remirepo.net/enterprise/remi-release-7.rpm -y
    fi
    yum install yum-utils -y
    yum-config-manager --disable 'remi-php*' -y
    yum-config-manager --enable   remi-php74 -y
elif [ ${OS_VERSION_MAJOR} -eq 8 ]
then
    dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm -y
    if ! rpm -qa | grep -q remi-release
    then
      yum install https://rpms.remirepo.net/enterprise/remi-release-8.rpm -y
    fi
    dnf module reset php -y
    dnf module install php:remi-7.4 -y
elif [ ${OS_VERSION_MAJOR} -eq 9 ]
then
    dnf install https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm -y
    if ! rpm -qa | grep -q remi-release
    then
      dnf install dnf-utils http://rpms.remirepo.net/enterprise/remi-release-9.rpm -y
    fi
    dnf module reset php -y
    dnf module enable php:remi-8.1 -y
    dnf install php -y
else
  exit 1
fi
