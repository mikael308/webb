#!/usr/bin/env bash
#
# facade for database operations
#
# author Mikael Holmbom

case $1 in

    "init")
sudo sh $0 setup
sudo sh $0 tables:setup
;;
    "setup")
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/setup_database.sql
SHELL
;;
    "drop")
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/drop_database.sql
SHELL
;;
    "reset")
sudo sh $0 database:drop
sudo sh $0 database:setup
;;

    "tables:clear") 
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/clear_tables.sql
SHELL
;;
    "tables:drop")
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/drop_tables.sql
SHELL
;;  
    "tables:reset")
sudo sh $0 tables:drop
sudo sh $0 tables:setup
;;
    "tables:setup") 
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/setup_tables.sql
psql -U postgres -d postgres -f /vagrant/setup/database/setup_storedprocedures.sql
psql -U postgres -d postgres -f /vagrant/setup/database/init_roles.sql
SHELL
;;

    "dummy")
php /vagrant/setup/database/dummy.php
;;

    *)
echo "invalid command: [$1]"
;;

esac

