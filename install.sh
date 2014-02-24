#!/bin/bash
wget http://uk1.php.net/distributions/php-5.4.15.tar.bz2
apt-get update
apt-get upgrade -yq
apt-get -yq install bzip2 openssl libxml2 libxml2-dev gcc make autoconf htop sudo git dstat
apt-get -yq ybuild-dep php5
tar xvjf php-5*.tar.bz2
cd php-5*
cd ext/
git clone git://github.com/krakjoe/pthreads.git
cd ../
./buildconf --force
./configure --enable-maintainer-zts --enable-sockets --enable-pcntl --enable-pthreads --with-tsrm-pthreads --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pgsql=/usr --with-tidy=/usr  --with-openssl=/usr/local/ssl  --with-pdo-pgsql=/usr --with-pdo-mysql=mysqlnd --with-xsl=/usr --enable-zip --with-pear  --with-gd --with-jpeg-dir=/usr --with-png-dir=/usr --with-mcrypt=/usr  --with-config-file-path=/usr/local/lib/
#--with-curl=/usr/bin

#./configure --enable-opcache --prefix=/opt/php --with-apxs2=/usr/bin/apxs2  --with-zlib-dir=/usr --with-xpm-dir=/usr --with-ldap --with-xmlrpc --with-iconv-dir=/usr --with-snmp=/usr --enable-exif --enable-calendar --with-bz2=/usr   --with-freetype-dir=/usr --enable-mbstring --with-libdir=/lib/x86_64-linux-gnu --with-config-file-path=/opt
make clean
make
#make test