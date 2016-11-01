DROP TABLE IF EXISTS proj.news, proj.forumsubjects, proj.roles, proj.forumposts, proj.forumusers, proj.forumthreads CASCADE;

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
	registered TIMESTAMP NOT NULL
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
	created TIMESTAMP NOT NULL
);


CREATE TABLE proj.news(
	id SERIAL NOT NULL PRIMARY KEY,
	author TEXT NOT NULL references proj.forumusers,
	title TEXT NOT NULL,
	message TEXT NOT NULL,
	created TIMESTAMP NOT NULL
);


---- INIT VALUES

INSERT INTO proj.roles(id, title)
VALUES
(0, 'admin'),
(1, 'moderator'),
(2, 'user');

INSERT INTO proj.forumusers (name, email, password, role, banned)
VALUES
('tom', 'tom@gmail.com', 'abc', 1, 'FALSE'),
('tim', 'tim@gmail.com', 'hej', 0, 'FALSE'),
('mikael', 'mik@gmail.com', 'asd', 2, 'FALSE');

INSERT INTO proj.forumsubjects(topic, subtitle)
VALUES
('news', 'threads about news'),
('general', 'not non-general stuff'),
('klaatu', 'verada nikto');

INSERT INTO proj.forumthreads (subject, topic)
VALUES
(1, 'kokbok'),
(2, 'rabalder'),
(2, 'nightclubbing');

INSERT INTO proj.forumposts (author, thread, message, created)
VALUES
('tom', 1, 'klaatu verada nikto', to_timestamp('16-05-2013 15:36:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 1, 'whut?', to_timestamp('16-05-2013 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 2, 'rummel, where is he know?', to_timestamp('16-05-2011 15:36:38', 'dd-mm-yyyy hh24:mi:ss')),
('tom', 1, 'bird is the word!', now()),
('tim', 3, 'bright nightclubbing', now()),
('mikael', 3, 'whats going on?', now());

INSERT INTO proj.news(author, title, message, created)
VALUES
('tom', 'init header', 'init the forum', to_timestamp('16-05-2009 15:26:38', 'dd-mm-yyyy hh24:mi:ss')),
('mikael', 'update', 'update the forum', to_timestamp('16-05-2013 11:36:38', 'dd-mm-yyyy hh24:mi:ss'));

