psql -U postgres -d postgres -a -f /vagrant/config/setup/setup_tables.sql
psql -U postgres -d postgres -a -f /vagrant/config/setup/init_roles.sql

php /vagrant/config/setup/setup_users.php

psql -U postgres -d postgres -a -f /vagrant/config/setup/init_vals.sql
psql -U postgres -d postgres -a -f /vagrant/config/setup/setup_storedprocedures.sql
php /vagrant/config/setup/setup_forumposts.php
