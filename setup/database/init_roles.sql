-- Inserts the forumuser roles
--
-- author Mikael Holmbom

INSERT INTO proj.roles(id, title)
VALUES
(0, 'admin'),
(1, 'moderator'),
(2, 'user');
