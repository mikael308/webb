
exec psql -U mikael -d postgres -a -f setup_tables.sql
exec php setup_users.php
exec psql init_vals.sql
