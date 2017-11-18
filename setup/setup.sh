#!/usr/bin/env bash
#
# author Mikael Holmbom

echo "Running setup setup"
echo " ********** setup config ************* "
sudo bash /vagrant/setup/setup_config.sh
echo " ********** setup LAMP *************** "
sudo bash /vagrant/setup/setup_lamp.sh
echo " ********** setup database *********** "
sudo bash /vagrant/setup/setup_database.sh
