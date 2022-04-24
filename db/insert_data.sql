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
    'Spinnign',
    'Allenamento con <span xml:lang="fr">cyclette</span> professionali Tehnogym',
    '2022-01-02',
    '2022-12-02' 
  );