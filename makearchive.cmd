@echo off
REM $Id$

REM Set Features to exclude
set grain_feature_excludes=--exclude=func/moonsugar*.php --exclude=lib/FirePHPCore

REM Compress
call makearchive-moonsugar.cmd