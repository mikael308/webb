# webb
simple forum to handle 
* subjects, threads and users with different roles
* searchfunction for authorized users
for demonstration see doc/presentation.ogv
## spec
- php7.0
- apache2
- postgresql

## setup database
to setup the database, do one of the following
### shellscript
1. in root, run ```bash config/setup/setup.sh```
### manual setup
run the following scripts
1. ./config/setup/setup_tables.sql
2. ./config/setup/init_roles.sql
3. ./config/setup/setup_users.php
4. ./config/setup/init_vals.sql
5. ./config/setup/setup_storedprocedures.sql

