#!/usr/bin/env bash
#
# setup the database
#
# author Mikael Holmbom

sudo apt-get -y install postgresql postgresql-contrib postgresql-client postgresql-client-common
sudo service apache2 restart

sudo su postgres <<SHELL
echo "setup_database.sql"
psql -U postgres -d postgres -f /vagrant/setup/database/setup_database.sql
echo "setup_tables.sql"
psql -U postgres -d postgres -f /vagrant/setup/database/setup_tables.sql
echo "setup_storedprocedures.sql"
psql -U postgres -d postgres -f /vagrant/setup/database/setup_storedprocedures.sql

# init values
psql -U postgres -d postgres -f /vagrant/setup/database/init_roles.sql

SHELL
