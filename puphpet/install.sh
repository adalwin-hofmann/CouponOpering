#!/bin/bash

# this must be run as root
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

# must be run from virtual machine
if [[ ! -d "/vagrant" || "$(hostname)" != "saveon" ]]; then
    echo "Aborting. /vagrant doesn't exist and your hostname is not 'saveon'. Are you sure you're on the virtual machine?"
    exit 1
fi

# uninstall and reinstall apache2 and php 5
echo "Uninstalling and reinstalling apache2 and php5"
apt-get --yes purge apache2 php5
apt-get --yes install apache2 php5 php5-curl

echo "Disabling default site"
a2dissite 15-default.conf

echo "Enabling saveon vhost"
a2ensite 25-saveon.conf

echo "Enabling mods: headers, rewrite"
a2enmod headers
a2enmod rewrite
service apache2 restart

echo "Removing html directory created by apache"
rm -rf /vagrant/html

echo "Creating the .env.local file"
if [ -f /vagrant/.env.local.php ]
then
    now=`date +"%m_%d_%Y"`
    mv /vagrant/.env.local.php "/vagrant/.env.local.old.$now.php"
    echo "A backup of your local env file has been created. Remove it if you no longer need it."
fi

cp /vagrant/puphpet/env.local.php /vagrant/.env.local.php

echo "Fixing saveon mysql user permissions"
/usr/bin/mysql -uroot -p123 -e "GRANT ALL ON *.* TO 'saveon'@'localhost'; FLUSH PRIVILEGES;"

echo "Finished!"

