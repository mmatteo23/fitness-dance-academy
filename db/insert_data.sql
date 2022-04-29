INSERT INTO ruolo (id, descrizione)
VALUES (1, 'Amministratore'), (2, 'Trainer');

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
    '05/08/1997',
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
    'Danilo',
    'Stojkovic',
    'ds@ds.it',
    '20/09/2000',
    'pass',
    '33333333333',
    'M',
    NULL,
    2,
    185,
    68
),
(
    3,
    'Matteo',
    'Casonato',
    'matteo@casonato.com',
    '08/08/2000',
    'admin',
    '3923240890',
    'M',
    NULL,
    2,
    186,
    78
);

INSERT INTO corso (
    id,
    titolo,
    descrizione,
    data_inizio,
    data_fine,
    trainer
  )
VALUES (
    1,
    'Total Body',
    'Allenamento di tutto il corpo con poche pause',
    '2022-01-02',
    '2022-12-02',
    1
  ),
  (
    2,
    'ZumbaFit',
    'Allenamento Full Body a passi di Zumba per tutte le età',
    '2022-01-02',
    '2022-12-02',
    1
  ),
  (
    3,
    'Spinning',
    'Allenamento con <span xml:lang="fr">cyclette</span> professionali Tehnogym',
    '2022-01-02',
    '2022-12-02',
    1
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
('stretching');

INSERT INTO esercizio(nome, categoria) 
VALUES 
('Plank', 1), 
('Crunch', 1), 
('Trazioni', 2), 
('Pressa spalla', 3), 
('Tapis roulant', 4), 
('Quadricipiti', 5), 
('Salto corda', 5), 
('Manubri', 6), 
('Affondi', 5), 
('Squat', 7), 
('Cyclette', 4), 
('Curl bilancere', 6), 
('Panca piana', 6), 
('Plank laterale', 1), 
('Stretching gambe', 8);

INSERT INTO  scheda(data, cliente, trainer) VALUES ("2022-04-26", 2, 1);

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