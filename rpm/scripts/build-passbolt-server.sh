#!/bin/sh

set -eu

if [ -f /usr/bin/zypper ]
then
  _OS_FAMILY="suse"
  zypper install -y rpmdevtools rpmlint rsync libselinux-devel rpm-build bc jq
else
  _OS_FAMILY="rhel"
  yum install -y rpmdevtools rpmlint rsync selinux-policy-devel rpm-build bc
  OS_VERSION=$(grep -E '^VERSION_ID=' /etc/os-release | awk -F= '{print $2}' | sed 's/\"//g')
  OS_VERSION_MAJOR=$(echo ${OS_VERSION:0:1} | bc)

  if [ ${OS_VERSION_MAJOR} -eq 7 ] || [ ${OS_VERSION_MAJOR} -eq 8 ] || [ ${OS_VERSION_MAJOR} -eq 9 ]
  then
    if ! rpm -qa | grep -q epel-release
    then
      yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-${OS_VERSION_MAJOR}.noarch.rpm -y
    fi
  fi
  yum install -y jq
fi

SCRIPT_DIR="$( cd "$( dirname "${0}" )" && pwd )"
PKG_VERSION=$(cat $SCRIPT_DIR/../CHANGELOG.md | awk 'match($0, /\[([0-9]+\.[0-9]+\.[0-9]+)\]?/) {print substr($0, RSTART, RLENGTH);exit}' | tr -d "[]")
PASSBOLT_PKG_VERSION=$(cat $SCRIPT_DIR/../CHANGELOG.md | awk 'match($0, /\[([0-9]+\.[0-9]+\.[0-9]+\-[0-9])\]?/) {print substr($0, RSTART, RLENGTH);exit}' | awk -F "-" '{print $2}' | tr -d "[]")
cd ${SCRIPT_DIR}/../..
PASSBOLT_DIR=$(basename $PWD)

rpmdev-setuptree

cp -r rpm/_passbolt-configure .
cp -r rpm/cron.d .
cp -r rpm/logrotate.d .
cd ..
rsync -a --delete --delete-excluded \
  --exclude debian \
  --exclude rpm \
  --exclude tests \
  --exclude .git \
  --exclude *deb \
  $PASSBOLT_DIR/ passbolt-${PASSBOLT_FLAVOUR}-server-${PKG_VERSION}
tar \
    --exclude-vcs \
    --exclude debian \
    --exclude rpm \
    --exclude tests \
    --gzip \
    --create \
    --file ~/rpmbuild/SOURCES/passbolt-${PASSBOLT_FLAVOUR}-server-${PKG_VERSION}.tar.gz \
    passbolt-${PASSBOLT_FLAVOUR}-server-${PKG_VERSION}
cd -
cp rpm/patches/*.diff ~/rpmbuild/SOURCES
cp rpm/specs/* ~/rpmbuild/SPECS/

rpmbuild -ba \
  --define "_passbolt_flavour ${PASSBOLT_FLAVOUR}" \
  --define "_passbolt_version ${PKG_VERSION}" \
  --define "_passbolt_package_version ${PASSBOLT_PKG_VERSION}" \
  --define "_os_family ${_OS_FAMILY}" \
  ~/rpmbuild/SPECS/passbolt-server.spec
