#
# setup the database
#
# author Mikael Holmbom

sudo apt-get -y install postgresql postgresql-contrib postgresql-client postgresql-client-common
sudo service apache2 restart

sudo su postgres <<SHELL
echo "setup_database.sql"
psql -U postgres -d postgres -f /vagrant/bootstrap/database/setup_database.sql
echo "setup_tables.sql"
psql -U postgres -d postgres -f /vagrant/bootstrap/database/setup_tables.sql
echo "setup_storedprocedures.sql"
psql -U postgres -d postgres -f /vagrant/bootstrap/database/setup_storedprocedures.sql

# init values
psql -U postgres -d postgres -f /vagrant/bootstrap/database/init_roles.sql

# add dummy values
php /vagrant/bootstrap/database/setup_users.php 
psql -U postgres -d postgres -f /vagrant/bootstrap/database/init_vals.sql
php /vagrant/bootstrap/database/setup_forumposts.php

SHELL
