
# Install Vagrant

http://www.vagrantup.com/downloads

# Install a virtualization provider

Recommended: [VirtualBox](https://www.virtualbox.org/wiki/Downloads)

# Set up the box (this will take some time)

Change into your Platform-SOE4 directory and type:

    vagrant up

This will take multiple minutes, especially if it has to download the precise64 box. You will see some errors at the end of the install about apache2 not starting.

    vagrant ssh

This will drop you at the ssh prompt of the virutal machine that was just created.

Run the postinstall script that comes with vagrant

    sudo ./postinstall.sh

The repository is mounted in /vagrant now. Take a look around.

    cd /vagrant
    ls

# Custom Post-install script

Now change into the puphpet directory.

    cd /vagrant/puphpet

Run install.sh as root

    sudo ./install.sh

# Data Migration

Choose one of the following: Migrate from existing data OR migrate from production

## Migrating from your existing data

From your current environment, run the following commands in order.

Run migrate on your current environment. This is important if the database configuration has diverged since you exported your sql database. Use your local username and password for the mysqldump command. Move it to your repository.

    # FROM THE OLD ENVIRONMENT
    php artisan migrate                         #At this point, you may need to temporarily revert to the old .env.local.php file backup
    mysqldump -uroot -p saveon > saveon.sql     #The mysqldump command may not be found on macs, use: /Applications/MAMP/Library/bin/mysqldump
    mv saveon.sql /path/to/Platform-SOE4/

WARNING: It is important to not run artisan migrate on the new vagrant box if you're trying to migrate from your previous environment. If you do, you may cause inconsistencies. If this happens, the solution is to drop the saveon database.

SSH into your vagrant box and change into the /vagrant directory (your mounted Platform-SOE4 directory). Import the data you exported from your previous environment.

    vagrant ssh
    cd /vagrant
    mysql -usaveon -psaveon123 saveon < saveon.sql  #Make sure that you are using the new .env.local.php file again

Clean up. Delete the .sql file once you're done. Don't commit it to the repository!

    rm saveon.sql

## Making a box from scratch using production data

If you already migrated from your existing data, you can skip this section.

Run artisan migrate to create the tables

    php artisan migrate

Manually import the data files. You can get them from someone here. Store them in your repository so you can access them from /vagrant. Make sure to locally ignore them, or delete them before you start working. You don't want to commit them to the repository.

    [05:51 PM]-[root@saveon]-[~]
    # mysql -u saveon -p --local-infile=1 saveon
    Enter password:
    Reading table information for completion of table and column names
    You can turn off this feature to get a quicker startup with -A

    Welcome to the MySQL monitor.  Commands end with ; or \g.
    Your MySQL connection id is 770
    Server version: 5.5.35-0ubuntu0.12.04.2 (Ubuntu)

    Copyright (c) 2000, 2013, Oracle and/or its affiliates. All rights reserved.

    Oracle is a registered trademark of Oracle Corporation and/or its
    affiliates. Other names may be trademarks of their respective
    owners.

    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

    mysql> load data local infile '/vagrant/import_data/contests-2-13.csv' into table saveon.contests fields terminated by ',' enclosed by '"' lines terminated by '\n';
    Query OK, 68 rows affected, 10 warnings (0.06 sec)
    Records: 69  Deleted: 0  Skipped: 1  Warnings: 10

Run the custom import script. This one may take over an hour. Look at it first to see what it does.

    php /vagrant/puphpet/import.php

# Verify that the setup is complete

Point your brower to localhost:8000. You should see the SaveOn dev home page with no errors.

# Migrate Slugs

Be sure to migrate slugs:

    cd /vagrant
    php artisan slug

# Update your local /etc/hosts
# IGNORE THIS STEP - dev environment can only be accessed through localhost:8000
Right now your local /etc/hosts file allows you to type things like "saveon.dev" or "soe.dev" into your browser to get to the dev site. In order to set this up, open up your /etc/hosts and edit your entry so it looks something like:

    saveon.dev  localhost:8000

# Accessing your database through SqlPro
To access your database on the vagrant box using SqlPro, you must connect through SSH:
    MySQL Host:     127.0.0.1
    Username:       saveon
    Password:       saveon123
    SSH Host:       localhost
    SSH User:       vagrant
    SSH Password:   vagrant
    SSH Port:       2222

# Suspending your Vagrant box

When you're done working for the day, you can suspend your vagrant box.

    vagrant suspend

When you're ready to start developing again, just type:

    vagrant up
    vagrant ssh

# Disk Space

With a fresh install, this virtual machine takes up about 3.5 GB. It will get larger as you add more data to the database or install new applications.

Your virtual machine is stored where ever VirtualBox puts its default machines. It may be in /Users/YourUserName/VirtualBox\ VMs or it may be in /home/YourUserName/.virtualbox for example.

    11:44:35 apollitt@laptop VirtualBox VMs du -h Platform-SOE4_default_1394200987667_79381
    60K     Platform-SOE4_default_1394200987667_79381/Logs
    3.5G    Platform-SOE4_default_1394200987667_79381

# Server Details

The Linux distribution used for this virutal machine is [Ubuntu Server 12.04 LTS](
) 64 (Precise Pangolin). We used this distribution because it is the same one that is run on the production server. LTS stands for Long Term Support, which means the Ubuntu developers will continue to release bug fixes for 5 years after it was released.

You can read  more about Ubuntu at [Ubuntu.com](http://www.ubuntu.com/server). Ubuntu has an excellent community. Head over to the forums if you have questions about how to administer an Ubuntu Server box.

# Troubleshooting

If at any point you can't seem to recover from something that happened during the install, you can destroy your vagrant box and start the process over again.

    vagrant destroy

Vagrant and PuPHPet are fairly new open source projects, and they have bugs. The /vagrant/puphpet/install.sh script helps recover from some of the bugs and configuration option failures.

