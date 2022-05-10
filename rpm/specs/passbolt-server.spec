Name:           passbolt-%{_passbolt_flavour}-server
Version:        %{_passbolt_version}
Release:        %{_passbolt_package_version}
Summary:        Passbolt
BuildArch:      noarch

License:        AGPL v3 | https://opensource.org/licenses/AGPL-3.0
URL:            https://www.passbolt.com
Source0:        %{name}-%{version}.tar.gz
Patch0:         02_webpaths_setup.diff
Patch1:         01_paths_setup.diff
Patch2:         03_cake_import_paths.diff

%include %{_specdir}/%{_os_family}-variables.inc
%include %{_specdir}/%{_os_family}-requires.inc

%description
Passbolt on RPM POC

%prep
%setup -q
%patch0 -p1
%patch1 -p1
%patch2 -p1

%install
rm -rf $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT/%{_sysconfdir}/cron.d
mkdir -p $RPM_BUILD_ROOT/%{_sysconfdir}/logrotate.d
mkdir -p $RPM_BUILD_ROOT/%{_datadir}/php/passbolt/logs
mkdir -p $RPM_BUILD_ROOT/%{_datadir}/passbolt/examples
mkdir -p $RPM_BUILD_ROOT/%{_datadir}/doc/passbolt-%{_passbolt_flavour}-server
mkdir -p $RPM_BUILD_ROOT/usr/local/bin
cp -r config $RPM_BUILD_ROOT/%{_sysconfdir}/passbolt/
cp -r config/app.default.php $RPM_BUILD_ROOT/%{_sysconfdir}/passbolt/app.php
rm -f $RPM_BUILD_ROOT/%{_sysconfdir}/passbolt/gpg/*
rm -f $RPM_BUILD_ROOT/%{_sysconfdir}/passbolt/jwt/*
cp -r cron.d/passbolt-server $RPM_BUILD_ROOT/%{_sysconfdir}/cron.d/passbolt-%{_passbolt_flavour}-server
cp -r logrotate.d/passbolt-server $RPM_BUILD_ROOT/%{_sysconfdir}/logrotate.d/passbolt-%{_passbolt_flavour}-server
cp -r index.php $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r bin $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
rm -f $RPM_BUILD_ROOT/%{_datadir}/php/passbolt/bin/{test,ci-test}
cp -r plugins $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r resources $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r src $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r templates $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r vendor $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r webroot $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r _passbolt-configure/* $RPM_BUILD_ROOT/usr/local/bin

%clean
rm -rf $RPM_BUILD_ROOT

%files
%{_datadir}/php/passbolt
%{_datadir}/php/passbolt/index.php
%{_datadir}/php/passbolt/bin
%{_datadir}/php/passbolt/logs
%{_datadir}/passbolt/examples
%{_datadir}/doc/passbolt-%{_passbolt_flavour}-server
%{_datadir}/php/passbolt/plugins
%{_datadir}/php/passbolt/resources
%{_datadir}/php/passbolt/src
%{_datadir}/php/passbolt/templates
%{_datadir}/php/passbolt/vendor
%{_datadir}/php/passbolt/webroot
%{_sysconfdir}/passbolt
%{_sysconfdir}/cron.d/passbolt-%{_passbolt_flavour}-server
%{_sysconfdir}/logrotate.d/passbolt-%{_passbolt_flavour}-server
/usr/local/bin/conf
/usr/local/bin/passbolt-configure

%post
chmod +x /usr/local/bin/passbolt-configure
mkdir -p /var/lib/passbolt/{.gnupg,tmp}
mkdir -p /var/log/passbolt
# Adjust some system directory permissions
chown -R %{_nginx_user}:%{_nginx_group} /var/lib/passbolt
chown -R %{_nginx_user}:%{_nginx_group} /var/log/passbolt
# Img public folder should be writeable by nginx
chown -R %{_nginx_user}:%{_nginx_group} %{_datadir}/php/passbolt/webroot/img/public
chmod 0644 %{_datadir}/php/passbolt/webroot/img/public/empty
chown -R root:%{_nginx_group} %{_sysconfdir}/passbolt
# No file should be executable except bin/cake
chmod -R -x+X %{_sysconfdir}/passbolt
chmod +x %{_datadir}/php/passbolt/bin/cake
chmod +x %{_datadir}/php/passbolt/bin/cake.php
chmod +x %{_datadir}/php/passbolt/bin/cron
chmod +x %{_datadir}/php/passbolt/bin/healthcheck
chmod +x %{_datadir}/php/passbolt/bin/status-report
chmod +x %{_datadir}/php/passbolt/bin/versions
# Configuration files not readable by others
chmod -R o-rw %{_sysconfdir}/passbolt/*
# nginx needs to write on /etc/passbolt for webinstaller
chmod 0770 %{_sysconfdir}/passbolt
chmod 0770 %{_sysconfdir}/passbolt/gpg
chmod 0770 %{_sysconfdir}/passbolt/jwt
chmod -R o-rwx %{_sysconfdir}/passbolt/*
# Strict permissions for gnupg server keyring
chmod 0700 /var/lib/passbolt/.gnupg/
sed -i 's/@@NGINX_USER@@/%{_nginx_user}/g' %{_sysconfdir}/cron.d/passbolt-%{_passbolt_flavour}-server

if [ -f /usr/bin/zypper ]
then
  echo "extension=gnupg.so" > /etc/php7/conf.d/gnupg.ini
fi

set_jwt_keys() {
  if [[ ! -f $jwt_key || ! -f $jwt_pem ]]
  then
    local jwt_dir='%{_sysconfdir}/passbolt/jwt'
    local jwt_key="$jwt_dir/jwt.key"
    local jwt_pem="$jwt_dir/jwt.pem"
    su -c '/usr/share/php/passbolt/bin/cake passbolt create_jwt_keys' -s /bin/bash "%{_nginx_user}"
    chmod 640 "$jwt_key" && chown root:%{_nginx_group} "$jwt_key"
    chmod 640 "$jwt_pem" && chown root:%{_nginx_group} "$jwt_pem"
  fi
}

# https://docs.fedoraproject.org/en-US/packaging-guidelines/Scriptlets/
if [ $1 -gt 1 ]
then
    su -c '%{_datadir}/php/passbolt/bin/cake passbolt migrate' -s /bin/bash %{_nginx_user} >> /var/log/passbolt/upgrade.log
    su -c '%{_datadir}/php/passbolt/bin/cake cache clear_all' -s /bin/bash %{_nginx_user} >> /var/log/passbolt/upgrade.log
    set_jwt_keys
fi

set_jwt_keys

%preun
if [ $1 -eq 0 ]
then
  rm -rf '%{_sysconfdir}/passbolt/jwt'
fi
%changelog
