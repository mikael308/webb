-- drops all tables
--
-- author Mikael Holmbom

DROP TABLE IF EXISTS 
proj.news,
proj.forumsubjects,
proj.roles,
proj.forumposts,
proj.forumusers, 
proj.forumthreads
CASCADE;
