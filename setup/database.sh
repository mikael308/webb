#!/usr/bin/env bash
#
# facade for database operations
#
# author Mikael Holmbom

case $1 in

    "init")
sudo sh $0 create
sudo sh $0 tables:create
;;
    "create")

sudo su postgres <<SHELL
createdb postgres
echo "create database"
psql -U postgres -d postgres -f /vagrant/setup/database/create_database.sql
echo "create stored procedures"
psql -U postgres -d postgres -f /vagrant/setup/database/create_storedprocedures.sql
SHELL
;;
    "drop")
sudo su postgres <<SHELL
dropdb postgres
SHELL
;;
    "reset")
sudo sh $0 drop
sudo sh $0 create
;;

    "tables:clear") 
sudo su postgres <<SHELL
psql -U postgres -d postgres -f /vagrant/setup/database/clear_tables.sql
SHELL
;;
    "tables:drop")
sudo su postgres <<SHELL
echo "drop tables"
psql -U postgres -d postgres -f /vagrant/setup/database/drop_tables.sql
SHELL
;;  
    "tables:reset")
sudo sh $0 tables:drop
sudo sh $0 tables:create
;;
    "tables:create")
sudo su postgres <<SHELL
echo "create tables"
psql -U postgres -d postgres -f /vagrant/setup/database/create_tables.sql
echo "init roles"
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
