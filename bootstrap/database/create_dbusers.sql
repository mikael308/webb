DROP USER ps_user;
CREATE USER ps_user  WITH PASSWORD 'ps_password';
GRANT ALL PRIVILEGES ON DATABASE postgres TO ps_user;
