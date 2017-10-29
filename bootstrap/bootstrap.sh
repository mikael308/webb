#
# author Mikael Holmbom

echo "Running bootstrap setup"
echo " ********** setup config ************* "
sudo bash /vagrant/bootstrap/setup_config.sh
echo " ********** setup LAMP *************** "
sudo bash /vagrant/bootstrap/setup_lamp.sh
echo " ********** setup database *********** "
sudo bash /vagrant/bootstrap/setup_database.sh

