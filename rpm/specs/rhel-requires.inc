Requires:       php >= 7.3
Requires:       php-cli >= 7.3
Requires:       php-mbstring >= 7.3
Requires:       php-intl >= 7.3
Requires:       php-mysqlnd >= 7.3
Requires:       php-fpm >= 7.3
Requires:       php-xml >= 7.3
Requires:       php-gd >= 7.3
Requires:       php-process >= 7.3
Requires:       php-json >= 7.3
Requires:       php-pecl-gnupg
%if "%{_passbolt_flavour}" == "pro"
Requires:       php-ldap >= 7.3
%endif
Requires:       cronie
Requires:       nginx
Requires:       mariadb-server
Requires:       passbolt-server-selinux
Requires:       haveged
