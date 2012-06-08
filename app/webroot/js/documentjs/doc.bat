@echo off
:: this file is a batch script that invokes loader.bat
:: ex: documentjs/document cookbook/cookbook.html

:: relative path to this script
set BASE=%~dps0
set CMD=%0

:: classpath
SET CP=%BASE%../steal/rhino/js.jar

:: load the run.js file
SET LOADPATH=%BASE%scripts/run.js

:: call js.bat
CALL %BASE%../steal/rhino/loader.bat %1 %2 %3 %4 %5 %6