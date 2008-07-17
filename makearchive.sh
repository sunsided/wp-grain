#!/bin/sh

# Set Features to exclude
export grain_feature_excludes="--exclude=func/moonsugar*.php --exclude=lib/FirePHPCore"

# Compress
./makearchive-moonsugar.sh

