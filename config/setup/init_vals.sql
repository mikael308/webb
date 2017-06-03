--------------------------------------------
--
--	 INIT VALUES
--
--------------------------------------------

INSERT INTO proj.forumsubjects(topic, subtitle)
VALUES
('news', 'threads about news'),
('general', 'not non-general stuff'),
('other', 'not like any other, just ordinary other');

INSERT INTO proj.forumthreads (subject, topic)
VALUES
(1, 'kokbok'),
(2, 'klaatu'),
(2, 'pigeon'),
(2, 'seagul');

INSERT INTO proj.forumposts (author, thread, message, created)
VALUES
('tom', 4, 'klaatu verada nikto', to_timestamp('16-05-1981 15:36:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 4, 'whut?', to_timestamp('16-05-2008 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 4, 'Lorem ipsum dolor sit amet, vel id tollit audiam sanctus, no quem reque accusamus qui. Sonet copiosae senserit usu an. Eu vix antiopam accusamus consulatu, an volumus perpetua eam. Ut alii nostro mea, sit movet congue tantas ut.

Cum in antiopam conceptam suscipiantur, vim ad augue vitae, an clita oblique mea. Labores graecis ex mei. Sit ea quod omnis consequat, cum in maiorum sadipscing. Id scribentur delicatissimi sit, ei vis movet timeam. Qui doming vulputate at, ad ornatus oporteat sea, vix liber dictas splendide et. No tollit malorum quaestio nam.

Te esse dicta definitionem his, atqui eripuit albucius pri eu, est ut alii consulatu. Solum utinam te usu, prima eloquentiam no mei. Id nec quando vocent moderatius, cu mei primis animal reprimique. In pro facer dissentiunt, reque disputando id eum. Congue quodsi omittantur usu ad, vim dolore ancillae et, per noster aliquip fastidii ea. Graeco option ex mel, mucius oportere his te, nam probo iisque voluptua no.', to_timestamp('16-05-2011 15:36:38', 'dd-mm-yyyy hh24:mi:ss')),
('klaatu',2, ' I am leaving soon, and you will forgive me if I speak bluntly. The universe grows smaller every day, and the threat of aggression by any group, anywhere, can no longer be tolerated. There must be security for all, or no one is secure. Now, this does not mean giving up any freedom, except the freedom to act irresponsibly. Your ancestors knew this when they made laws to govern themselves and hired policemen to enforce them. We, of the other planets, have long accepted this principle. We have an organization for the mutual protection of all planets and for the complete elimination of aggression. The test of any such higher authority is, of course, the police force that supports it. For our policemen, we created a race of robots. Their function is to patrol the planets in spaceships like this one and preserve the peace. In matters of aggression, we have given them absolute power over us. This power cannot be revoked. At the first sign of violence, they act automatically against the aggressor. The penalty for provoking their action is too terrible to risk. The result is, we live in peace, without arms or armies, secure in the knowledge that we are free from aggression and war. Free to pursue more... profitable enterprises. Now, we do not pretend to have achieved perfection, but we do have a system, and it works. I came here to give you these facts. It is no concern of ours how you run your own planet, but if you threaten to extend your violence, this Earth of yours will be reduced to a burned-out cinder. Your choice is simple: join us and live in peace, or pursue your present course and face obliteration. We shall be waiting for your answer. The decision rests with you. ',to_timestamp('16-05-2008 15:45:38', 'dd-mm-yyyy hh24:mi:ss')),
('tom', 1, 'bird is the word!', to_timestamp('16-05-2010 15:41:18', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'bright nightclubbing', to_timestamp('16-05-2010 15:50:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'a', to_timestamp('16-05-2013 15:40:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'b', to_timestamp('16-05-2013 15:41:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'c', to_timestamp('16-05-2013 15:42:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'd', to_timestamp('16-05-2013 15:43:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'e', to_timestamp('16-05-2013 15:44:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'f', to_timestamp('16-05-2013 15:45:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'g', to_timestamp('16-05-2013 15:46:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'ih', to_timestamp('16-05-2013 15:47:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'j', to_timestamp('16-05-2013 15:48:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'k', to_timestamp('16-05-2013 15:50:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'l', to_timestamp('16-05-2013 15:50:48', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'm', to_timestamp('16-05-2013 15:51:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'n', to_timestamp('16-05-2013 15:52:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'o', to_timestamp('16-05-2013 15:53:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'p', to_timestamp('16-05-2013 15:54:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'q', to_timestamp('16-05-2013 15:55:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'r', to_timestamp('16-05-2013 15:56:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'ns', to_timestamp('16-05-2013 15:57:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'nt', to_timestamp('16-05-2013 15:58:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'u', to_timestamp('16-05-2013 15:59:38', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'v', to_timestamp('16-05-2013 15:59:48', 'dd-mm-yyyy hh24:mi:ss')),
('hilda', 3, 'x', to_timestamp('16-05-2013 15:59:50', 'dd-mm-yyyy hh24:mi:ss')),

('mikael', 3, 'whats going on?', to_timestamp('16-05-2013 12:40:38', 'dd-mm-yyyy hh24:mi:ss'));

INSERT INTO proj.news(author, title, message, created)
VALUES
('tom', 'init header', 'init the forum', to_timestamp('16-05-2009 15:26:38', 'dd-mm-yyyy hh24:mi:ss')),
('mikael', 'update', 'update the forum', to_timestamp('16-05-2013 11:36:38', 'dd-mm-yyyy hh24:mi:ss'));
