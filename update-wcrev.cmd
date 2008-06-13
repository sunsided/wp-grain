@echo off
REM $Id$

REM Die, if necessary
if not exist func/moonsugar.base.php goto END

REM SubWCRev
subwcrev . ./func/moonsugar.base.php ./func/moonsugar.php

:END