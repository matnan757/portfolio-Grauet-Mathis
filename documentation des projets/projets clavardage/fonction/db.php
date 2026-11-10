<?php
// Connexion partagee a la base locale SQLite de Clavardon.
// Aucune installation requise : le fichier clavarde.db est cree automatiquement,
// les tables et quelques comptes de demonstration aussi au premier lancement.

function clavarde_connect(): PDO
{
    static $db = null;
    if ($db instanceof PDO) {
        return $db;
    }

    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../clavarde.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $db->exec('CREATE TABLE IF NOT EXISTS user (
        id_user   INTEGER PRIMARY KEY AUTOINCREMENT,
        login     TEXT UNIQUE NOT NULL,
        email     TEXT,
        password  TEXT NOT NULL,
        admin     INTEGER NOT NULL DEFAULT 0
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS discussion (
        id_discussion INTEGER PRIMARY KEY AUTOINCREMENT,
        membre1       TEXT NOT NULL,
        membre2       TEXT NOT NULL
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS chat (
        id_message    INTEGER PRIMARY KEY AUTOINCREMENT,
        envoyeur      TEXT NOT NULL,
        message       TEXT NOT NULL,
        date          TEXT NOT NULL,
        id_discussion INTEGER NOT NULL
    )');

    // Donnees de demonstration, inserees une seule fois.
    $nb = (int) $db->query('SELECT count(*) FROM user')->fetchColumn();
    if ($nb === 0) {
        $db->exec("INSERT INTO user (login, email, password, admin) VALUES
            ('alice', 'alice@test.fr', 'alice', 0),
            ('bob',   'bob@test.fr',   'bob',   0),
            ('admin', 'admin@test.fr', 'admin', 1)");
        $db->exec("INSERT INTO discussion (membre1, membre2) VALUES ('alice', 'bob')");
        $db->exec("INSERT INTO chat (envoyeur, message, date, id_discussion) VALUES
            ('alice', 'Salut Bob !',            '2026-01-01 10:00:00', 1),
            ('bob',   'Salut Alice, ca va ?',   '2026-01-01 10:01:00', 1)");
    }

    return $db;
}
