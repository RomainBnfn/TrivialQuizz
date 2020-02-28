DROP TABLE IF EXISTS joueur;
DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS theme;
DROP TABLE IF EXISTS reponse;
DROP TABLE IF EXISTS quiz_quest;
DROP TABLE IF EXISTS questions;

CREATE TABLE joueur
(
  jo_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  jo_pseudo VARCHAR(20) NOT NULL,
  jo_password VARCHAR(20) NOT NULL
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE admin
(
  ad_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ad_pseudo VARCHAR(20) NOT NULL,
  ad_password VARCHAR(20) NOT NULL
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE theme
(
  th_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  th_nom VARCHAR(50) NOT NULL,
  th_couleur VARCHAR(7) NOT NULL,
  th_description VARCHAR(1000)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE quiz
(
  qui_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  qui_desc VARCHAR(20) NOT NULL,
  th_id INTEGER NOT NULL,
  FOREIGN KEY (th_id) REFERENCES theme(th_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE score
(
  sc_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  sc_point INTEGER NOT NULL,
  sc_temps INTEGER NOT NULL,
  sc_date DATE NOT NULL,
  jo_id INTEGER NOT NULL,
  qui_id INTEGER NOT NULL,
  FOREIGN KEY (jo_id) REFERENCES joueur(jo_id),
  FOREIGN KEY (qui_id) REFERENCES quiz(qui_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE questions
(
  que_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  que_lib VARCHAR(500) NOT NULL,
  re_id_bonnerep INTEGER NOT NULL,
  qui_id INTEGER NOT NULL,
  FOREIGN KEY (qui_id) REFERENCES quiz(qui_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE reponses
(
  re_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  re_lib VARCHAR(200),
  que_id INTEGER,
  FOREIGN KEY (que_id) REFERENCES questions(que_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE quiz_quest
(
  qui_id INTEGER NOT NULL PRIMARY KEY,
  que_id  INTEGER NOT NULL PRIMARY KEY,
  FOREIGN KEY (qui_id) REFERENCES quiz(qui_id),
  FOREIGN KEY (que_id) REFERENCES questions(que_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

ALTER TABLE questions
ADD FOREIGN KEY (re_id_bonnerep) REFERENCES reponses(re_id)
