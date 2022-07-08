#!/bin/sh

set -eu

if [ -f /usr/bin/zypper ]
then
  REPOPATH=/etc/zypp/repos.d
  PKGNAME=zypper
else
  REPOPATH=/etc/yum.repos.d
  PKGNAME=yum
fi

if [ ! -d ~/rpmbuild/RPMS ]
then
  ${PKGNAME} install -y rpmdevtools
  rpmdev-setuptree
  mkdir ~/rpmbuild/RPMS/noarch
  cp passbolt-*.rpm ~/rpmbuild/RPMS/noarch
fi

createrepo_c --update ~/rpmbuild/RPMS

cat << EOF | tee ${REPOPATH}/local.repo
[local]
name=Local Repository Demo
baseurl=file:///root/rpmbuild/RPMS/
enabled=1
gpgcheck=0
protect=1
EOF
