DROP TABLE IF EXISTS prenotazione_scheda;
DROP TABLE IF EXISTS esercizio_scheda;
DROP TABLE IF EXISTS prenotazione_evento;
DROP TABLE IF EXISTS prenotazione_sessione;
DROP TABLE IF EXISTS iscrizione_corso;
DROP TABLE IF EXISTS scheda;
DROP TABLE IF EXISTS ruolo;
DROP TABLE IF EXISTS corso;
DROP TABLE IF EXISTS utente;
DROP TABLE IF EXISTS evento;
DROP TABLE IF EXISTS esercizio;
DROP TABLE IF EXISTS categoria;

CREATE TABLE ruolo (
	id int,
	descrizione varchar(100),

	PRIMARY KEY (id)
);

CREATE TABLE utente (
	id int NOT NULL,
	nome varchar(200) NOT NULL,
	cognome varchar(200) NOT NULL,
	email varchar(255) NOT NULL UNIQUE,
	data_nascita date NOT NULL,
	password varchar(255) NOT NULL,
	telefono varchar(10),
	sesso char NOT NULL,
	foto_profilo varchar(255),
	ruolo int NOT NULL,
	altezza int,
	peso int,

	PRIMARY KEY (id)
);

CREATE TABLE evento (
	id int NOT NULL,
	titolo varchar(255) NOT NULL,
	descrizione varchar(255) NOT NULL,
	data_inizio datetime NOT NULL,
	data_fine datetime NOT NULL,
	copertina varchar(255) NOT NULL,

	PRIMARY KEY (id)
);

CREATE TABLE prenotazione_evento (
	cliente int,
	evento int,

	PRIMARY KEY (cliente, evento),
	FOREIGN KEY (cliente) REFERENCES utente(id),
	FOREIGN KEY (evento) REFERENCES evento(id)
);

CREATE TABLE scheda (
	id int NOT NULL AUTO_INCREMENT,
	data date NOT NULL,
	cliente int NOT NULL,
	trainer int NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (cliente) REFERENCES utente(id),
	FOREIGN KEY (trainer) REFERENCES utente(id)
);

CREATE TABLE prenotazione_scheda (
	id 				int AUTO_INCREMENT,
	cliente			int NOT NULL,
	trainer			int NOT NULL,
	data 			datetime NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (cliente) REFERENCES utente(id),
	FOREIGN KEY (trainer) REFERENCES utente(id)
);

CREATE TABLE categoria (
	id int NOT NULL AUTO_INCREMENT,
	descrizione varchar(200) NOT NULL,

	PRIMARY KEY (id)
);

CREATE TABLE esercizio (
	id int NOT NULL AUTO_INCREMENT,
	nome varchar(200) NOT NULL,
	categoria int NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (categoria) REFERENCES categoria(id)
);

CREATE TABLE esercizio_scheda (
	scheda int NOT NULL,
	esercizio int NOT NULL,
	serie int NOT NULL,
	ripetizioni int NOT NULL,
	riposo int NOT NULL,
	/*foto_esercizio varchar(255) NOT NULL,*/

	PRIMARY KEY (scheda, esercizio),
	FOREIGN KEY (esercizio) REFERENCES esercizio(id)
);

CREATE TABLE prenotazione_sessione (
	id int AUTO_INCREMENT,
	data date NOT NULL,
	ora_inizio time NOT NULL,
	ora_fine time NOT NULL,
	cliente int NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (cliente) REFERENCES utente(id)
);

CREATE TABLE corso (
	id int AUTO_INCREMENT,
	titolo varchar(200) NOT NULL,
	descrizione varchar(255) NOT NULL,
	data_inizio datetime NOT NULL,
	data_fine datetime NOT NULL,
	copertina varchar(255),
	trainer int NOT NULL,

	PRIMARY KEY (id),
	FOREIGN KEY (trainer) REFERENCES utente(id)
);

CREATE TABLE iscrizione_corso (
	cliente int,
	corso int,

	PRIMARY KEY (cliente, corso),
	FOREIGN KEY (cliente) REFERENCES utente(id),
	FOREIGN KEY (corso) REFERENCES corso(id)
);
