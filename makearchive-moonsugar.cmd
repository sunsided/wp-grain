@echo off
REM $Id$

REM Set archive name(s)
set grain_tar=grain-archive.tar
set grain_tgz=%grain_tar%.gz

REM Set Features to exclude
set grain_feature_excludes=%grain_feature_excludes% --exclude=./iplugs/devalvr --exclude=./iplugs/ptviewer

REM Update version
if exist update-wcrev.cmd call update-wcrev.cmd

REM Compress
tar cf - . --exclude=.svn --exclude=*.psd --exclude=*.tgz --exclude=.htaccess --exclude=*.tar.gz --exclude=./po --exclude=*.tdl --exclude=*.cmd --exclude=*.bat --exclude=*.sh --exclude=*.py --exclude=%grain_tar%* %grain_feature_excludes% | gzip --best --verbose > %grain_tgz%