--
-- STORED PROCEDURES
-- author Mikael Holmbom

CREATE OR REPLACE FUNCTION proj.get_user(IN p_name text DEFAULT '%'::text)
 RETURNS TABLE(
  id INTEGER,
  name TEXT,
  email TEXT,
  role INTEGER,
  banned boolean,
  registered timestamp
 ) AS $$
 BEGIN
  RETURN QUERY SELECT
  u.id,
  u.name,
  u.email, 
  CAST(u.role AS INTEGER),
  CAST(u.banned AS BOOLEAN),
  u.registered
  FROM proj.forumusers as u
  WHERE u.name LIKE p_name;
 END; $$

LANGUAGE PLPGSQL;
 
CREATE OR REPLACE FUNCTION proj.get_post(IN p_msg TEXT DEFAULT '%'::text )
 RETURNS TABLE(
  author INTEGER,
  thread INTEGER,
  id INTEGER,
  message TEXT,
  created TIMESTAMP,
  edited TIMESTAMP
 ) AS $$
 BEGIN
  RETURN QUERY SELECT
  *
  FROM proj.forumposts AS p
  WHERE p.message LIKE p_msg;
 END; $$
 
 LANGUAGE PLPGSQL;
