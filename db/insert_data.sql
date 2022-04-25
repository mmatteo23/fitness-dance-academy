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
);

INSERT INTO corso (
    id,
    titolo,
    descrizione,
    data_inizio,
    data_fine
  )
VALUES (
    1,
    'Total Body',
    'Allenamento di tutto il corpo con poche pause',
    '2022-01-02',
    '2022-12-02'
  ),
  (
    2,
    'ZumbaFit',
    'Allenamento Full Body a passi di Zumba per tutte le età',
    '2022-01-02',
    '2022-12-02'
  ),
  (
    3,
    'Spinning',
    'Allenamento con <span xml:lang="fr">cyclette</span> professionali Tehnogym',
    '2022-01-02',
    '2022-12-02' 
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
('plank', 1), 
('crunch', 1), 
('trazioni', 2), 
('pressa spalla', 3), 
('tapis roulant', 4), 
('quadricipiti', 5), 
('salto con corda', 5), 
('manubri', 6), 
('affondi', 5), 
('squat', 7), 
('cyclette', 4), 
('curl bilancere', 6), 
('panca piana', 6), 
('plank laterale', 1), 
('stretching gambe', 8);