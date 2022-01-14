#!/bin/sh

set -exu

SCRIPT_DIR="$( cd "$( dirname "${0}" )" && pwd )"
cd ${SCRIPT_DIR}/../..

yum install -y rpmdevtools rpmlint rsync selinux-policy-devel rpm-build bc
echo "setuptree"
rpmdev-setuptree

OS_VERSION=$(grep -E '^VERSION_ID=' /etc/os-release | awk -F= '{print $2}' | sed 's/\"//g')
OS_VERSION_MAJOR=$(echo ${OS_VERSION:0:1} | bc)
_POLICYCOREUTILS_PYTHON=policycoreutils-python
if [ ${OS_VERSION_MAJOR} -gt 7 ]
then
  _POLICYCOREUTILS_PYTHON=policycoreutils-python-utils
fi

cp rpm/specs/passbolt-server-selinux.spec ~/rpmbuild/SPECS/
cd rpm/passbolt-server-selinux
make clean
echo "make"
make
cd ..
tar --create \
    --gzip \
    --transform="flags=r;s|passbolt-server-selinux|passbolt-server-selinux-${PKG_VERSION}|" \
    --file ~/rpmbuild/SOURCES/passbolt-server-selinux-${PKG_VERSION}.tar.gz passbolt-server-selinux
cd -
make clean
rpmbuild -ba \
   --define "_passbolt_selinux_version ${PKG_VERSION}" \
   --define "_policycoreutils_python ${_POLICYCOREUTILS_PYTHON}" \
   ~/rpmbuild/SPECS/passbolt-server-selinux.spec
