DROP TABLE IF EXISTS quiz_quest;
DROP TABLE IF EXISTS reponse;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS theme;
DROP TABLE IF EXISTS profil;

CREATE TABLE profil
(
  pr_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  pr_pseudo VARCHAR(20) NOT NULL,
  pr_password VARCHAR(20) NOT NULL,
  pr_is_admin BIT(1)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE theme
(
  th_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  th_nom VARCHAR(50) NOT NULL,
  th_couleur VARCHAR(7) NOT NULL,
  th_description VARCHAR(500),
  th_is_principal BIT(1)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE quiz
(
  qui_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  qui_nom VARCHAR(20) NOT NULL,
  qui_desc VARCHAR(200) NOT NULL,
  th_id INTEGER NOT NULL,
  FOREIGN KEY (th_id) REFERENCES theme(th_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE score
(
  sc_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  sc_point INTEGER NOT NULL,
  sc_temps INTEGER NOT NULL,
  sc_date DATE NOT NULL,
  pr_id INTEGER NOT NULL,
  qui_id INTEGER NOT NULL,
  FOREIGN KEY (pr_id) REFERENCES profil(pr_id),
  FOREIGN KEY (qui_id) REFERENCES quiz(qui_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE question
(
  que_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  que_lib VARCHAR(500) NOT NULL,
  que_type INTEGER NOT NULL
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE reponse
(
  re_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  re_lib VARCHAR(200) NOT NULL,
  re_isBonne BOOLEAN NOT NULL,
  que_id INTEGER NOT NULL,
  FOREIGN KEY (que_id) REFERENCES question(que_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) engine=innodb character set utf8 collate utf8_unicode_ci;

CREATE TABLE quiz_quest
(
  qui_id INTEGER NOT NULL,
  que_id  INTEGER NOT NULL,
  qq_order INTEGER NOT NULL,
  PRIMARY KEY (qui_id, que_id),
  FOREIGN KEY (qui_id) REFERENCES quiz(qui_id) ON DELETE CASCADE,
  FOREIGN KEY (que_id) REFERENCES question(que_id) ON DELETE CASCADE
) engine=innodb character set utf8 collate utf8_unicode_ci;
