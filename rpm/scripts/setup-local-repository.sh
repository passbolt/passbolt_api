#!/bin/sh

set -eu

createrepo --update ~/rpmbuild/RPMS

cat << EOF | tee /etc/yum.repos.d/local.repo
[local]
name=Local Repository Demo
baseurl=file:///root/rpmbuild/RPMS/
enabled=1
gpgcheck=0
protect=1
EOF