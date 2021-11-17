#!/bin/sh

set -eu

if [ ! -d ~/rpmbuild/RPMS ]
then
  yum install -y rpmdevtools
  rpmdev-setuptree
  mkdir ~/rpmbuild/RPMS/noarch
  cp passbolt-*.rpm ~/rpmbuild/RPMS/noarch
fi

createrepo --update ~/rpmbuild/RPMS

cat << EOF | tee /etc/yum.repos.d/local.repo
[local]
name=Local Repository Demo
baseurl=file:///root/rpmbuild/RPMS/
enabled=1
gpgcheck=0
protect=1
EOF