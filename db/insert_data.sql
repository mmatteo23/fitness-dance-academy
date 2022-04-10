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