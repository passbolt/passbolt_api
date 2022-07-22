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
Requires:       php7-openssl >= 7.3
Requires:       php7-pecl
Requires:       php7-gnupg
%if "%{_passbolt_flavour}" == "pro"
Requires:       php7-ldap >= 7.3
%endif
Requires:       cronie
Requires:       nginx
