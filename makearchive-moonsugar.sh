#/bin/sh

# Set archive name(s)
export grain_base="grain-archive"
export grain_tar="$grain_base.tar"
export grain_tgz="$grain_base.tgz"

# Set Features to exclude
export grain_feature_excludes="$grain_feature_excludes --exclude=iplugs/devalvr --exclude=iplugs/ptviewer"

# Update version
if [ -f ./update-wcrev.sh ]; then
	./update-wcrev.sh
fi

# Compress
tar cf - ../grain --mode=777 --no-same-owner --exclude=func/moonsugar.base.php --exclude=.svn --exclude=*.psd --exclude=*.tgz --exclude=*.log --exclude=*.tar.gz --exclude=*.zip --exclude=po --exclude=*.tdl --exclude=*.cmd --exclude=*.bat --exclude=*.*~ --exclude=*.sh --exclude=*.py --exclude=$grain_tar* $grain_feature_excludes | gzip --best --verbose > $grain_tgz

