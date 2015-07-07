#!/bin/sh

# ============================================================
# Backend application
# init submodules
git submodule update --init

# copy the core configuration file, change the cypher seed and salt
cp app/Config/core.php.default app/Config/core.php

# copy the database configuration file
cp app/Config/database.php.default app/Config/database.php

# edit the credentials
nano app/Config/database.php

# copy the app configuration file
cp app/Config/app.php.default app/Config/app.php

# change seeds
nano app/Config/core.php

# run the install script
./app/Console/cake install

# ============================================================
# Front-end application
# copy front end config
cp app/webroot/js/app/config/config.json.default app/webroot/js/app/config/config.json

# edit config
nano app/webroot/js/app/config/config.json

# ============================================================
# CSS and front end tooling
# install grunt for less to css
sudo npm install -g grunt-cli

# install the needed modules defined in the grunt config
npm install


