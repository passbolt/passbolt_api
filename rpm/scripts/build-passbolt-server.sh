#!/bin/sh

set -eu

SCRIPT_DIR="$( cd "$( dirname "${0}" )" && pwd )"
cd ${SCRIPT_DIR}/../..

yum install -y rpmdevtools rpmlint rsync selinux-policy-devel rpm-build bc
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
  passbolt/ passbolt-${PASSBOLT_FLAVOUR}-server-${PKG_VERSION}
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
cp rpm/specs/passbolt-server.spec ~/rpmbuild/SPECS/
rpmbuild -ba \
  --define "_passbolt_flavour ${PASSBOLT_FLAVOUR}" \
  --define "_passbolt_version ${PKG_VERSION}" \
  ~/rpmbuild/SPECS/passbolt-server.spec