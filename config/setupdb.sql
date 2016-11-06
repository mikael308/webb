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

INSERT INTO proj.forumusers (name, email, password, role, banned, registered)
VALUES
('tom', 'tom@gmail.com', 'abc', 1, 'FALSE', to_timestamp('16-05-2010 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 'tim@gmail.com', 'hej', 0, 'FALSE', to_timestamp('16-05-2012 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('mikael', 'mik@gmail.com', 'asd', 2, 'FALSE', to_timestamp('16-05-2014 15:40:38', 'dd-mm-yyyy hh24:mi:ss'));

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
('tim', 2, 'Lorem ipsum dolor sit amet, vel id tollit audiam sanctus, no quem reque accusamus qui. Sonet copiosae senserit usu an. Eu vix antiopam accusamus consulatu, an volumus perpetua eam. Ut alii nostro mea, sit movet congue tantas ut.

Cum in antiopam conceptam suscipiantur, vim ad augue vitae, an clita oblique mea. Labores graecis ex mei. Sit ea quod omnis consequat, cum in maiorum sadipscing. Id scribentur delicatissimi sit, ei vis movet timeam. Qui doming vulputate at, ad ornatus oporteat sea, vix liber dictas splendide et. No tollit malorum quaestio nam.

Te esse dicta definitionem his, atqui eripuit albucius pri eu, est ut alii consulatu. Solum utinam te usu, prima eloquentiam no mei. Id nec quando vocent moderatius, cu mei primis animal reprimique. In pro facer dissentiunt, reque disputando id eum. Congue quodsi omittantur usu ad, vim dolore ancillae et, per noster aliquip fastidii ea. Graeco option ex mel, mucius oportere his te, nam probo iisque voluptua no.', to_timestamp('16-05-2011 15:36:38', 'dd-mm-yyyy hh24:mi:ss')),
('tom', 1, 'bird is the word!', to_timestamp('16-05-2010 15:41:18', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'bright nightclubbing', to_timestamp('16-05-2010 15:50:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'a', to_timestamp('16-05-2013 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'b', to_timestamp('16-05-2013 15:41:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'c', to_timestamp('16-05-2013 15:42:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'd', to_timestamp('16-05-2013 15:43:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'e', to_timestamp('16-05-2013 15:44:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'f', to_timestamp('16-05-2013 15:45:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'g', to_timestamp('16-05-2013 15:46:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'ih', to_timestamp('16-05-2013 15:47:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'j', to_timestamp('16-05-2013 15:48:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'k', to_timestamp('16-05-2013 15:50:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'l', to_timestamp('16-05-2013 15:50:48', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'm', to_timestamp('16-05-2013 15:51:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'n', to_timestamp('16-05-2013 15:52:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'o', to_timestamp('16-05-2013 15:53:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'p', to_timestamp('16-05-2013 15:54:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'q', to_timestamp('16-05-2013 15:55:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'r', to_timestamp('16-05-2013 15:56:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'ns', to_timestamp('16-05-2013 15:57:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'nt', to_timestamp('16-05-2013 15:58:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'u', to_timestamp('16-05-2013 15:59:38', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'v', to_timestamp('16-05-2013 15:59:48', 'dd-mm-yyyy hh24:mi:ss')),
('tim', 3, 'x', to_timestamp('16-05-2013 15:59:50', 'dd-mm-yyyy hh24:mi:ss')),

('mikael', 3, 'whats going on?', to_timestamp('16-05-2013 12:40:38', 'dd-mm-yyyy hh24:mi:ss'));

INSERT INTO proj.news(author, title, message, created)
VALUES
('tom', 'init header', 'init the forum', to_timestamp('16-05-2009 15:26:38', 'dd-mm-yyyy hh24:mi:ss')),
('mikael', 'update', 'update the forum', to_timestamp('16-05-2013 11:36:38', 'dd-mm-yyyy hh24:mi:ss'));

