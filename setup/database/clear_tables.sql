-- clear all data in tables
--
-- author Mikael Holmbom

DELETE FROM proj.news CASCADE;
DELETE FROM proj.forumposts CASCADE;
DELETE FROM proj.forumthreads CASCADE;
DELETE FROM proj.forumusers CASCADE;
DELETE FROM proj.forumsubjects CASCADE;
