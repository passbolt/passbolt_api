#!/bin/bash

sudo apt-get update -qq
sudo apt-get install -qq libssh2-1-dev libssh2-php
echo "extension = mongo.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
touch .interactive
(pecl install -f ssh2 < .interactive)

if grep -Fxq "ssh2" ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
then
    echo "SSH2 extension was already installed. ";
else
    echo "SSH2 was not enabled during installation. Enabling it manually";
    echo "extension = ssh2.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
fi

cp tests/Gaufrette/Functional/adapters/DoctrineDbal.php.dist tests/Gaufrette/Functional/adapters/DoctrineDbal.php -f
