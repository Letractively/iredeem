#!/bin/sh

version="$1"
git tag -a $version -m "dist"
git clone . .clone
cd .clone
mv lite iRedeem-lite-$version
7za a iRedeem-lite-$version.7z iRedeem-lite-$version
mv iRedeem-lite-$version.7z ../
cd ..
rm -R .clone
