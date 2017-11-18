#!/usr/bin/env bash
#
# setup the database
#
# author Mikael Holmbom

sudo apt-get -y install postgresql postgresql-contrib postgresql-client postgresql-client-common
sudo service apache2 restart
