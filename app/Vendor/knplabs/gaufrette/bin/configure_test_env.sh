#!/bin/bash

sudo apt-get update -qq
sudo apt-get install -qq libssh2-1-dev libssh2-php
echo "extension = mongo.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
touch .interactive
(pecl install -f ssh2 < .interactive)

cp tests/Gaufrette/Functional/adapters/DoctrineDbal.php.dist tests/Gaufrette/Functional/adapters/DoctrineDbal.php -f
