#!/usr/bin/env bash
#
#
# author Mikael Holmbom
#

sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

# apache
sudo apt-get install -y apache2
# php
sudo apt-get install -y php7.0
sudo apt-get install -y php7.0-cli php7.0-mbstring php7.0-mcrypt php7.0-json php7.0-pgsql php7.0-xml 


# APACHE2 CONFIG
###############################


echo " *********** WRITING TO php.ini *****************"

cat <<EOF >> /etc/php/7.0/apache2/php.ini
auto_prepend_file="/vagrant/setup/autoprepend.php"
EOF

##################################################################################

echo " *********** WRITING TO sites-enabled/000-default.CONF *****************"

mkdir /vagrant/var
mkdir /vagrant/var/log
mkdir /vagrant/var/log/apache

cat <<EOF > /etc/apache2/sites-enabled/000-default.conf

ServerName 10.0.2.15

<VirtualHost *:80>
  DocumentRoot "/vagrant/"
  DirectoryIndex src/index.phtml

  ErrorLog /vagrant/var/log/apache/error.log
  CustomLog /vagrant/var/log/apache/access.log combined

  <Directory "/">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
  </Directory>

  AccessFileName .htaccess

</VirtualHost>

EOF


sudo a2enmod rewrite
sudo service apache2 restart
