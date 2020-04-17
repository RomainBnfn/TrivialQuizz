/**
    CREATION DES 6 THEMES Principaux : Présents sur la roue
**/
INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Animaux",
  "#2E95F2",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Arts et Culture",
  "#674EA7",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Sport",
  "#60C932",
  "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Géographie",
  "#F1D332",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Histoire",
  "#FFAB40",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  1
);
INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Sciences",
  "#F553AC",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  1
);
/**
    CREATION DE 2 THEMES Secondaires : Présents sous la roue
**/
INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Aventure",
  "#478880",
  "Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum.",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Jardinage",
  "#5B8847",
  "Duis aute  eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat lit anim id est laborum. Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ucitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ",
  0
);
/**
    CREATION DE 9 Quizzes : Répartis un peu partout
**/
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  300 ,
  10 ,
  1
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  400 ,
  5 ,
  2
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  200 ,
  0 ,
  3
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  300 ,
  0 ,
  4
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  200 ,
  5 ,
  5
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  500 ,
  15 ,
  6
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  500 ,
  15 ,
  7
);
INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Excepteur sint",
  "Excepteur sint occaecat cupidatat lit anim id est laborum. Excepteur sint occaecat cupidatat lit anim id est laborum",
  600 ,
  10 ,
  7
);

/*** CREATION DES QUESTIONS + Quiz-Quest - REPONSES ****/

/** QUESTION ID = 1 */
  INSERT INTO question (que_lib, que_type) VALUES
  (
    "Pourquoi l'herbe est verte?",
    2
  );

  /*** RÉPONSES ***/
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse juste",
    1,
    1
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    1
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    1
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    1
  );


/** QUESTION  ID = 2 */
  INSERT INTO question (que_lib, que_type) VALUES
  (
    "Pourquoi l'eau est bleue?",
    2
  );

  /*** RÉPONSES ***/
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse juste",
    1,
    2
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    2
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    2
  );
  INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
  (
    "Réponse fausse",
    0,
    2
  );

/*** association quizz avec question ****/
/*
  On donne des questions à tous les quizs pour qu'ils soient affichés.
*/
  INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
  (
    1,
    1,
    1
  );
  INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
  (
    2,
    1,
    1
  );
/** QUESTION ID = 3 **/
INSERT INTO question (que_lib, que_type) VALUES
(
  "Question libre ?",
  1
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Réponse libre",
  1,
  3
);
/*
  On associe la même question à tous les quizz
*/
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  3,
  3,
  2
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  4,
  1,
  1
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  5,
  1,
  1
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  6,
  1,
  1
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  7,
  1,
  1
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  8,
  1,
  1
);
