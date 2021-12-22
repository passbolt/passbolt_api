Name:           passbolt-%{_passbolt_flavour}-server
Version:        %{_passbolt_version}
Release:        1%{?dist}
Summary:        Passbolt
BuildArch:      noarch

License:        WTFPL | http://www.wtfpl.net/
URL:            https://www.passbolt.com
Source0:        %{name}-%{version}.tar.gz
Patch0:         02_webpaths_setup.diff
Patch1:         01_paths_setup.diff
Patch2:         03_cake_import_paths.diff

Requires:       php7 >= 7.3
Requires:       php7-cli >= 7.3
Requires:       php7-mbstring >= 7.3
Requires:       php7-intl >= 7.3
Requires:       php7-mysql >= 7.3
Requires:       php7-fpm >= 7.3
Requires:       php7-xmlreader >= 7.3
Requires:       php7-xmlwriter >= 7.3
Requires:       php7-gd >= 7.3
Requires:       php7-json >= 7.3
Requires:       php7-curl >= 7.3
Requires:       php7-posix >= 7.3
Requires:       php7-fileinfo >= 7.3
Requires:       php7-pecl
%if "%{_passbolt_flavour}" == "pro"
Requires:       php7-ldap >= 7.3
%endif
Requires:       cronie
Requires:       nginx
Requires:       mariadb

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
mkdir -p $RPM_BUILD_ROOT/%{_datadir}/php/passbolt/logs
mkdir -p $RPM_BUILD_ROOT/usr/local/bin
cp -r config $RPM_BUILD_ROOT/%{_sysconfdir}/passbolt/
cp -r cron.d/passbolt-server $RPM_BUILD_ROOT/%{_sysconfdir}/cron.d/passbolt-%{_passbolt_flavour}-server
cp -r index.php $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
cp -r bin $RPM_BUILD_ROOT/%{_datadir}/php/passbolt
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
%{_datadir}/php/passbolt/index.php
%{_datadir}/php/passbolt/bin
%{_datadir}/php/passbolt/logs
%{_datadir}/php/passbolt/plugins
%{_datadir}/php/passbolt/resources
%{_datadir}/php/passbolt/src
%{_datadir}/php/passbolt/templates
%{_datadir}/php/passbolt/vendor
%{_datadir}/php/passbolt/webroot
%{_sysconfdir}/passbolt
%{_sysconfdir}/cron.d/passbolt-%{_passbolt_flavour}-server
/usr/local/bin/conf
/usr/local/bin/passbolt-configure

%post 
chown -R nginx:nginx %{_datadir}/php/passbolt
chown -R nginx:nginx %{_sysconfdir}/passbolt/
chmod +x /usr/local/bin/passbolt-configure
mkdir -p /var/lib/passbolt/.gnupg
chown -R nginx:nginx /var/lib/passbolt
su - nginx -s /bin/bash -c "gpg --list-keys --home /var/lib/passbolt/.gnupg"
mkdir -p /var/log/passbolt
chown -R nginx:nginx /var/log/passbolt
# https://docs.fedoraproject.org/en-US/packaging-guidelines/Scriptlets/
if [ $1 -gt 1 ]
then
    su -c '/usr/share/php/passbolt/bin/cake passbolt migrate' -s /bin/bash nginx >> /var/log/passbolt/upgrade.log
    su -c '/usr/share/php/passbolt/bin/cake cache clear_all' -s /bin/bash nginx >> /var/log/passbolt/upgrade.log
fi

%changelog

