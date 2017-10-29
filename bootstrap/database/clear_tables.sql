-- clear all data in tables
--
-- author Mikael Holmbom

DELETE FROM
proj.news,
proj.forumposts,
proj.forumthreads,
proj.forumusers,
proj.forumsubjects 
CASCADE;
