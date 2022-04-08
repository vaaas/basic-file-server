#!/bin/sh

version='1.0.0'

# put files in proper directory for DEB file generation
basename='configuration-server'
dirname="$basename"_"$version"_amd64
mkdir -p $dirname/opt/$basename $dirname/etc/systemd/system
cp -pr --parents conf.php index.php lib start.sh $dirname/opt/$basename
cp -pr etc/configuration-server.service $dirname/etc/systemd/system
cp -pr DEBIAN $dirname
chmod 755 $dirname/DEBIAN
sed -i s/VERSION/$version/ $dirname/DEBIAN/control

# make the deb file
dpkg-deb --build --root-owner-group $dirname

# cleanup
rm -rf $dirname
