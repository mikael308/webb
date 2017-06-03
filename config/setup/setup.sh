psql -U mikael -d postgres -a -f ./config/setup/setup_tables.sql
psql -U mikael -d postgres -a -f ./config/setup/init_roles.sql

php ./config/setup/setup_users.php

psql -U mikael -d postgres -a -f ./config/setup/init_vals.sql
psql -U mikael -d postgres -a -f ./config/setup/setup_storedprocedures.sql
