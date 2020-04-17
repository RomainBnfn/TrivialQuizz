###########################################
####### 6 thèmes classiques de base #######
###########################################

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Animaux",
  "#2E95F2",
  "Arriverez-vous à nommer le cris de ces animaux?",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Arts et Culture",
  "#674EA7",
  "Êtes-vous incollable sur les noms des habitants des localités du monde entier?",
  1
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Sport",
  "#60C932",
  "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
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
  "",
  1
);
INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Sciences",
  "#F553AC",
  "",
  1
);

##############################################
####### Quelques thèmes custom de test #######
##############################################

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Aventure",
  "#478880",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Jardinage",
  "#5B8847",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Musique",
  "#884786",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Sport",
  "#D73C3D",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Célébrité",
  "#DC2091",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Histoire",
  "#98674B",
  "",
  0
);

INSERT INTO theme (th_nom, th_couleur, th_description, th_is_principal) VALUES
(
  "Science",
  "#314EC3",
  "",
  0
);


##########################
##### Quizz de test ######
##########################

INSERT INTO quiz (qui_nom, qui_desc, qui_temps, qui_malus, th_id) VALUES
(
  "Super quiz made in PAF",
  "Ce genre de description bien éclatée au sol, mais faut un mettre une ... donc me voilà !!",
  300 ,
  10 ,
  6
);

/*** QUESTION ****/

INSERT INTO question (que_lib, que_type) VALUES
(
  "Pourquoi l'herbe est verte?",
  2
);

INSERT INTO question (que_lib, que_type) VALUES
(
  "Lequel des ces surnoms n'appartient pas à un membre de la DGSE dans le bureaux des légendes?",
  2
);


/*** RÉPONSES ***/
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "T'es daltonien",
  0,
  1
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Parce que les poules ont des dents",
  0,
  1
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Je sais pas",
  1,
  1
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Parce que réponse 4",
  0,
  1
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Malotru",
  0,
  2
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "MAG",
  0,
  2
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "La mule",
  0,
  2
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Cyclone",
  0,
  2
);
INSERT INTO reponse (re_lib, re_isBonne, que_id) VALUES
(
  "Fantastique",
  1,
  2
);

/*** association quizz avec question ****/
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  1,
  1,
  1
);
INSERT INTO quiz_quest (qui_id, que_id, qq_order) VALUES
(
  1,
  2,
  2
);
