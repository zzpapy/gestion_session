#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE user(
        id_user Int  Auto_increment  NOT NULL ,
        sexe    Varchar (70) NOT NULL ,
        birth   Date NOT NULL ,
        ville   Varchar (70) NOT NULL ,
        email   Varchar (50) NOT NULL
	,CONSTRAINT user_PK PRIMARY KEY (id_user)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: session
#------------------------------------------------------------

CREATE TABLE session(
        id_session Int  Auto_increment  NOT NULL ,
        nom        Varchar (50) NOT NULL ,
        date_debut Date NOT NULL ,
        date_fin   Date NOT NULL ,
        nb_places  Float NOT NULL
	,CONSTRAINT session_PK PRIMARY KEY (id_session)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: categorie
#------------------------------------------------------------

CREATE TABLE categorie(
        id_categorie Int  Auto_increment  NOT NULL ,
        nom          Varchar (50) NOT NULL
	,CONSTRAINT categorie_PK PRIMARY KEY (id_categorie)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: module
#------------------------------------------------------------

CREATE TABLE module(
        id_module    Int  Auto_increment  NOT NULL ,
        nom          Varchar (11) NOT NULL ,
        id_categorie Int NOT NULL
	,CONSTRAINT module_PK PRIMARY KEY (id_module)

	,CONSTRAINT module_categorie_FK FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: user_session
#------------------------------------------------------------

CREATE TABLE user_session(
        id_session Int NOT NULL ,
        id_user    Int NOT NULL
	,CONSTRAINT user_session_PK PRIMARY KEY (id_session,id_user)

	,CONSTRAINT user_session_session_FK FOREIGN KEY (id_session) REFERENCES session(id_session)
	,CONSTRAINT user_session_user0_FK FOREIGN KEY (id_user) REFERENCES user(id_user)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: programme
#------------------------------------------------------------

CREATE TABLE programme(
        id_module  Int NOT NULL ,
        id_session Int NOT NULL ,
        duree      Varchar (11) NOT NULL
	,CONSTRAINT programme_PK PRIMARY KEY (id_module,id_session)

	,CONSTRAINT programme_module_FK FOREIGN KEY (id_module) REFERENCES module(id_module)
	,CONSTRAINT programme_session0_FK FOREIGN KEY (id_session) REFERENCES session(id_session)
)ENGINE=InnoDB;

