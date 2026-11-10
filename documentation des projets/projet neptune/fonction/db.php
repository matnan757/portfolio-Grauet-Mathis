<?php
// Connexion a la base locale SQLite de l'Hotel Neptune.
// Aucune installation requise : le fichier neptune.db, la table client et un
// compte administrateur de demonstration sont crees automatiquement.

function neptune_db(): PDO
{
    static $db = null;
    if ($db instanceof PDO) {
        return $db;
    }

    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../neptune.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $db->exec('CREATE TABLE IF NOT EXISTS client (
        id_client INTEGER PRIMARY KEY AUTOINCREMENT,
        login     TEXT UNIQUE NOT NULL,
        email     TEXT,
        password  TEXT NOT NULL,
        admin     INTEGER NOT NULL DEFAULT 0
    )');

    $db->exec('CREATE TABLE IF NOT EXISTS chambre (
        id_chambre  INTEGER PRIMARY KEY AUTOINCREMENT,
        nom         TEXT NOT NULL,
        description TEXT,
        prix        REAL NOT NULL,
        capacite    INTEGER NOT NULL DEFAULT 1,
        disponible  INTEGER NOT NULL DEFAULT 1
    )');

    // Compte administrateur de demonstration, cree une seule fois.
    $nb = (int) $db->query('SELECT count(*) FROM client')->fetchColumn();
    if ($nb === 0) {
        $stmt = $db->prepare('INSERT INTO client (login, email, password, admin)
                              VALUES (:login, :email, :password, :admin)');
        $stmt->execute([
            'login'    => 'admin',
            'email'    => 'admin@neptune.fr',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'admin'    => 1,
        ]);
    }

    // Chambres de demonstration, creees une seule fois.
    $nbc = (int) $db->query('SELECT count(*) FROM chambre')->fetchColumn();
    if ($nbc === 0) {
        $chambres = [
            ['Chambre Simple',      'Chambre confortable pour une personne, avec salle de bain privative et vue sur la ville.',          59.0, 1, 1],
            ['Chambre Double',      'Chambre spacieuse avec un grand lit double, ideale pour un couple.',                                 89.0, 2, 1],
            ['Chambre Twin',        'Chambre avec deux lits simples, parfaite pour des amis ou collegues.',                               85.0, 2, 1],
            ['Chambre Familiale',   'Grande chambre pouvant accueillir jusqu a quatre personnes, avec coin salon.',                      129.0, 4, 1],
            ['Suite Neptune',       'Notre suite de luxe avec vue sur la mer, salon separe et baignoire balneo.',                        249.0, 2, 1],
            ['Suite Presidentielle','Le summum du luxe : terrasse privee, jacuzzi et service de majordome.',                             399.0, 2, 0],
        ];
        $stmt = $db->prepare('INSERT INTO chambre (nom, description, prix, capacite, disponible)
                              VALUES (:nom, :description, :prix, :capacite, :disponible)');
        foreach ($chambres as $c) {
            $stmt->execute([
                'nom'         => $c[0],
                'description' => $c[1],
                'prix'        => $c[2],
                'capacite'    => $c[3],
                'disponible'  => $c[4],
            ]);
        }
    }

    return $db;
}

// Demarre la session une seule fois (evite l'avertissement de double demarrage).
function neptune_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
