SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS prenotazione_scheda;
DROP TABLE IF EXISTS esercizio_scheda;
DROP TABLE IF EXISTS prenotazione_sessione;
DROP TABLE IF EXISTS iscrizione_corso;
DROP TABLE IF EXISTS scheda;
DROP TABLE IF EXISTS ruolo;
DROP TABLE IF EXISTS corso;
DROP TABLE IF EXISTS utente;
DROP TABLE IF EXISTS esercizio;
DROP TABLE IF EXISTS categoria;
SET FOREIGN_KEY_CHECKS=1;

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
	id int,
	titolo varchar(200) NOT NULL,
	descrizione varchar(255) NOT NULL,
	data_inizio date NOT NULL,
	data_fine date NOT NULL,
	copertina varchar(255),
  alt_copertina varchar(255),
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

INSERT INTO ruolo (id, descrizione)
VALUES (1, 'Amministratore'), (2, 'Trainer'), (3, 'Cliente');

INSERT INTO utente (
    id,
    nome,
    cognome,
    email,
    data_nascita,
    password,
    telefono,
    sesso,
    foto_profilo,
    ruolo,
    altezza,
    peso)
VALUES 
(
    1,
    'Alberto',
    'Danieletto',
    'braccio.carota@fda.it',
    '2000-11-03',
    'selly6figa',
    '34567897894',
    'M',
    NULL,
    2,
    177,
    70
),
(
    2,
    'Selly',
    'Scheggia',
    'selly.scheggia@fda.it',
    '2000-07-15',
    'braccioTiAmo',
    '3859689456',
    'F',
    NULL,
    2,
    165,
    65
),
(
    3,
    'Danilo',
    'Stojkovic',
    'ds@ds.it',
    '2000-09-20',
    'pass',
    '33333333333',
    'M',
    NULL,
    3,
    185,
    68
),
(
    4,
    'Matteo',
    'Casonato',
    'matteo@casonato.com',
    '2000-08-08',
    'admin',
    '3923240890',
    'M',
    "3.png",
    1,
    186,
    78
),
(
    5,
    'Mattia',
    'Quasinato',
    'mattia@quasinato.com',
    '2001-08-08',
    'admin',
    '3923240890',
    'M',
    "3.png",
    3,
    186,
    78
);

INSERT INTO corso (
    id,
    titolo,
    descrizione,
    data_inizio,
    data_fine,
    trainer,
    copertina,
    alt_copertina
  )
VALUES (
    1,
    '<span xml:lang="en" lang="en">Total Body</span>',
    'Allenamento di tutto il corpo con poche pause',
    '2022-01-02',
    '2022-12-02',
    1,
    '1.jpg',
    'Due persone in posizione squat con un peso in mano'
  ),
  (
    2,
    '<span xml:lang="en" lang="en">ZumbaFit</span>',
    'Allenamento <span xml:lang="en" lang="en">Full Body</span> a passi di Zumba per tutte le et&agrave;',
    '2022-01-02',
    '2022-12-02',
    1,
    '2.jpg',
    'Ragazze che fanno esercizi di Zumba'
  ),
  (
    3,
    '<span xml:lang="en" lang="en">Spinning</span>',
    'Allenamento con <span xml:lang="fr">cyclette</span> professionali <span xml:lang="en" lang="en">Technogym</span>',
    '2022-01-02',
    '2022-12-02',
    1,
    '3.jpg',
    'Il nostro set di spin bike'
  ),
  (
    4,
    '<span xml:lang="en" lang="en">Break Ass</span>',
    'Allenamento che appena tutto il corso usando pause piccole e ritmi di ripetizioni alte. Riuscirai a resistere?',
    '2022-01-02',
    '2022-12-02',
    1,
    NULL,
    NULL
  );

INSERT INTO categoria(descrizione) 
VALUES 
('addominali'), 
('petto'), 
('spalla'), 
('cardio'), 
('gambe'), 
('braccia'), 
('glutei'),
('<span xml:lang="en" lang="en">stretching</span>');

INSERT INTO esercizio(nome, categoria) 
VALUES 
('<span xml:lang="en" lang="en">Plank</span>', 1), 
('<span xml:lang="en" lang="en">Crunch</span>', 1), 
('Trazioni', 2), 
('Pressa spalla', 3), 
('<span xml:lang="fr" lang="fr">Tapis roulant</span>', 4), 
('Quadricipiti', 5), 
('Salto corda', 5), 
('Manubri', 6), 
('Affondi', 5), 
('<span xml:lang="en" lang="en">Squat</span>', 7), 
('<span xml:lang="fr" lang="fr">Cyclette</span>', 4), 
('<span xml:lang="en" lang="en">Curl</span> bilancere', 6), 
('Panca piana', 6), 
('<span xml:lang="en" lang="en">Plank</span> laterale', 1), 
('<span xml:lang="en" lang="en">Stretching</span> gambe', 8);

INSERT INTO  scheda(data, cliente, trainer) VALUES ("2022-04-26", 3, 1);

INSERT INTO esercizio_scheda(scheda, esercizio, serie, ripetizioni, riposo) VALUES
(1, 3, 3, 10, 0),
(1, 1, 4, 15, 0),
(1, 7, 10, 1, 60),
(1, 12, 3, 7, 0),
(1, 13, 2, 1, 0),
(1, 10, 3, 3, 0),
(1, 5, 4, 5, 60),
(1, 9, 5, 5, 0),
(1, 2, 3, 20, 0);