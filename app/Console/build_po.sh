#!/bin/sh

APP_PATH=~/Workspace/passbolt

rm -fr $APP_PATH/app/Locale/default.pot
$APP_PATH/app/Console/cake i18n extract --paths $APP_PATH/app/webroot/js/passbolt/,$APP_PATH/app/webroot/js/lb/, --app $APP_PATH/app/ --merge yes --output $APP_PATH/app/Locale 
mv $APP_PATH/app/Locale/default.pot $APP_PATH/app/Locale/jsDictionnary.pot

