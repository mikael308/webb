-- creates tables
--
-- author Mikael Holmbom


CREATE TABLE proj.roles(
    id INTEGER NOT NULL PRIMARY KEY,
    title TEXT NOT NULL
);

CREATE TABLE proj.forumusers(
    name TEXT NOT NULL PRIMARY KEY,
    email TEXT NOT NULL,
    role INTEGER NOT NULL references proj.roles,
    banned BOOLEAN,
    password TEXT NOT NULL,
    registered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE proj.forumsubjects(
    id SERIAL NOT NULL PRIMARY KEY,
    topic TEXT NOT NULL,
    subtitle TEXT NOT NULL
);

CREATE TABLE proj.forumthreads(
    id SERIAL NOT NULL PRIMARY KEY,
    subject INTEGER NOT NULL references proj.forumsubjects,
    topic TEXT NOT NULL
);

CREATE TABLE proj.forumposts(
    author TEXT NOT NULL references proj.forumusers,
    thread INTEGER NOT NULL references proj.forumthreads,
    id SERIAL NOT NULL PRIMARY KEY,
    message TEXT NOT NULL,
    created TIMESTAMP NOT NULL,
    edited TIMESTAMP
);

CREATE TABLE proj.news(
    id SERIAL NOT NULL PRIMARY KEY,
    author TEXT NOT NULL references proj.forumusers,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    created TIMESTAMP NOT NULL
);
