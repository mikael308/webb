-- SETUP database
-- author Mikael Holmbom

-- CREATE DATABASE postgres -h 10.0.2.15 -p 5432 -U postgres postgres;

ALTER USER postgres WITH PASSWORD 'password';

-- CREATE DATABASE postgres;-- -p 5432 -U postgres -d postgres;
-- CREATE DATABASE ps_database;
CREATE SCHEMA proj;


-- CREATE ROLE 'admin' PASSWORD 'admin';
-- GRANT ALL PRIVILEGES ON DATABASE 'ps_database' to admin;

