<?php

//Grauet Mathis et Andries Alexis

$bateaux = [
    "porte_avions" => ["taille" => 5, "nb" => 1],
    "cuirasse"     => ["taille" => 4, "nb" => 1],
    "croiseur"     => ["taille" => 3, "nb" => 1],
    "sous_marin"   => ["taille" => 3, "nb" => 2],
    "destroyer"    => ["taille" => 2, "nb" => 1]
];

//Fonctions

function affichetab($tab){
    $m = count($tab);              // nombre de lignes
    $n = count($tab[0]);           // nombre de colonnes

    // Afficher les numéros de colonnes en haut
    echo "  ";
    for ($j = 0; $j < $n; $j++) {
        echo $j . " ";
    }
    echo "\n";
    
    // Afficher les lignes avec leurs numéros
    for ($i = 0; $i < $m; $i++) {
        echo $i . " ";  // Numéro de ligne
        for ($j = 0; $j < $n; $j++) {
            echo $tab[$i][$j] . " ";
        }
        echo "\n";
    }
    
    echo "------------\n";
}


function initialisegrille($lignes, $colonnes){
    $grille = [];

    for ($i = 0; $i < $lignes; $i++) {
        for ($j = 0; $j < $colonnes; $j++) {
            $grille[$i][$j] = 0;
        }
    }

    return $grille;
}

// Efface l'écran/les anciens messages pour la sortie CLI
function clearScreen(): void {
    // ANSI escape sequences pour la plupart des terminaux
    echo "\033[2J\033[H";
    // Ajout de lignes vides pour les consoles qui n'interprètent pas ANSI
    echo str_repeat("\n", 30);
}

function tire(int $ligne, int $colonne, &$grille, ?array &$pvBateaux = null, ?array $bateauxInfo = null, bool $showShipNames = true){
    // 0 = vide, 1 = bateau, 2 = touché, 3 = raté
    if ($grille[$ligne][$colonne] == 0){
        $grille[$ligne][$colonne]= 3;
        echo "Coup dans l'eau !\n";
        return true;
    }
    if ($grille[$ligne][$colonne] == 3 || $grille[$ligne][$colonne] == 2){
        echo "Tu as déjà tiré ici\n";
        return false;
    }
    if ($grille[$ligne][$colonne] == 1){
        $grille[$ligne][$colonne]= 2;
        
        // Gestion automatique des PV si les paramètres sont fournis
        if ($pvBateaux !== null && $bateauxInfo !== null) {
            $nomBateau = isset($bateauxInfo[$ligne][$colonne]) ? $bateauxInfo[$ligne][$colonne] : null;
            
            if ($nomBateau && isset($pvBateaux[$nomBateau])) {
                $pvBateaux[$nomBateau]--;
                
                if ($pvBateaux[$nomBateau] <= 0) {
                    if ($showShipNames) {
                        echo "💀 LE BATEAU $nomBateau EST COULÉ !\n";
                    } else {
                        // Message bien visible pour mode caché
                        echo "\n💥💥  COULÉ !  💥💥\n\n";
                    }
                } else {
                    if ($showShipNames) {
                        echo "Touché ! $nomBateau a encore " . $pvBateaux[$nomBateau] . " PV\n";
                    } else {
                        echo "Touché !\n";
                    }
                }
            } else {
                echo "Touché !\n";
            }
        } else {
            echo "Touché !\n";
        }
        
        return true;
    }
    return true;
}

// Fonction pour initialiser les PV de chaque bateau
function initPVBateaux(array $bateaux): array {
    $pvBateaux = [];
    foreach ($bateaux as $nomBateau => $info) {
        $nombre = $info["nb"];
        $taille = $info["taille"];
        
        // Si plusieurs instances du même bateau, les numéroter
        if ($nombre > 1) {
            for ($i = 1; $i <= $nombre; $i++) {
                $pvBateaux["$nomBateau #$i"] = $taille;
            }
        } else {
            $pvBateaux[$nomBateau] = $taille;
        }
    }
    
    return $pvBateaux;
}

function bateaux(array &$grille, int $ligne, int $colonne, string $sens, int $taille, string $nomBateau = '', ?array &$bateauxInfo = null): bool {
    //nombres de lignes et de colonne
    $nbLignes   = count($grille);
    $nbColonnes = isset($grille[0]) ? count($grille[0]) : 0;
    //test pour savoir si la personne a mis le bon mot dans $sens
    if (!in_array($sens, ['verticale', 'horizontal'], true)) {
        echo "Vous devez indiquer soit 'verticale' soit 'horizontal' pour le sens\n";
        return false;
    }

    // test
    if ($sens === 'verticale') {
        // test pour voir si on rentre dans la grille
        if ($ligne + $taille > $nbLignes) {
            echo "Dépassement vertical \n\n";
            return false;
        }
        for ($k = 0; $k < $taille; $k++) {
            // test pour voir si la case existe puis si elle est déjà utilisée
            if (!isset($grille[$ligne + $k][$colonne])) {
                echo "Tu es hors de la grille \n\n";
                return false;
            }
            if ($grille[$ligne + $k][$colonne] != 0) {
                echo "Un bateaux est déjà présent dans la case \n\n";
                return false;
            }
        }
        // on met le navire a sa place
        for ($k = 0; $k < $taille; $k++) {
            $grille[$ligne + $k][$colonne] = 1;
            // Enregistrer le nom du bateau à cette position
            if ($nomBateau && $bateauxInfo !== null) {
                $bateauxInfo[$ligne + $k][$colonne] = $nomBateau;
            }
        }
    } else { // test pour l'horizontal
        if ($colonne + $taille > $nbColonnes) {
            echo "Dépassement horizontal \n\n";
            return false;
        }
        for ($k = 0; $k < $taille; $k++) {
            if (!isset($grille[$ligne][$colonne + $k])) {
                echo "Tu es hors de la grille \n\n";
                return false;
            }
            if ($grille[$ligne][$colonne + $k] != 0) {
                echo "Un bateaux est déjà présent dans la case *\n\n";
                return false;
            }
        }
        for ($k = 0; $k < $taille; $k++) {
            $grille[$ligne][$colonne + $k] = 1;
            // Enregistrer le nom du bateau à cette position
            if ($nomBateau && $bateauxInfo !== null) {
                $bateauxInfo[$ligne][$colonne + $k] = $nomBateau;
            }
        }
    }
    // placement réussi
    return true;
}

//Placement



// Fonction pour placer tous les bateaux automatiquement (aléatoire)
function placerTousLesBateauxAuto(array &$grille, array &$bateauxInfo, array $bateaux): bool {
    $nbLignes   = count($grille);
    $nbColonnes = isset($grille[0]) ? count($grille[0]) : 0;
    $maxTentatives = 5000;
    
    // Créer un tableau de tous les bateaux à placer (mélanger l'ordre)
    $tousLesBateaux = [];
    foreach ($bateaux as $nomBateau => $info) {
        $taille = $info["taille"];
        $nombre = $info["nb"];
        for ($b = 0; $b < $nombre; $b++) {
            $nomBateauNum = $nombre > 1 ? "$nomBateau #" . ($b + 1) : $nomBateau;
            $tousLesBateaux[] = [
                "nom" => $nomBateauNum,
                "taille" => $taille
            ];
        }
    }
    
    // Mélanger aléatoirement l'ordre de placement
    shuffle($tousLesBateaux);
    
    // Placer chaque bateau dans l'ordre mélangé
    foreach ($tousLesBateaux as $bateau) {
        $nomBateauNum = $bateau["nom"];
        $taille = $bateau["taille"];
        $tentatives = 0;
        $placed = false;
        
        while (!$placed && $tentatives < $maxTentatives) {
            $ligne = rand(0, $nbLignes - 1);
            $colonne = rand(0, $nbColonnes - 1);
            $sens = (rand(0, 1) === 0) ? 'verticale' : 'horizontal';
            
            if (bateaux($grille, $ligne, $colonne, $sens, $taille, $nomBateauNum, $bateauxInfo)) {
                $placed = true;
                echo "✓ $nomBateauNum placé automatiquement\n";
            }
            $tentatives++;
        }
        
        if (!$placed) {
            echo "❌ Impossible de placer $nomBateauNum\n";
            return false;
        }
    }
    
    return true;
}

// Fonction pour placer tous les bateaux manuellement (ordre automatique)
function placerTousLesBateaux(array &$grille, array &$bateauxInfo, array $bateaux): bool {
    $totalPlaces = 0;
    $totalAplacer = 0;
    
    // Compter le nombre total de bateaux à placer
    foreach ($bateaux as $info) {
        $totalAplacer += $info["nb"];
    }
    
    // Pour chaque type de bateau (dans l'ordre du tableau)
    foreach ($bateaux as $nomBateau => $info) {
        $taille = $info["taille"];
        $nombre = $info["nb"];
        
        // Placer "nombre" bateaux de ce type
        for ($b = 0; $b < $nombre; $b++) {
            $placed = false;
            $nomBateauNum = $nombre > 1 ? "$nomBateau #" . ($b + 1) : $nomBateau;
            
            // Boucler jusqu'à ce que le joueur place correctement le bateau
            while (!$placed) {
                echo "\n========== PLACEMENT DE BATEAU ==========\n";
                echo "Bateau à placer : $nomBateauNum (taille: $taille)\n";
                echo "Progression : " . $totalPlaces . " / " . $totalAplacer . " bateaux placés\n";
                
                echo "\nGrille actuelle :\n";
                affichetab($grille);
                
                echo "Choisis la ligne où placer ton $nomBateauNum (ex : 0 à 9) : ";
                $ligne = trim(fgets(STDIN));
                
                echo "Choisis la colonne où placer ton $nomBateauNum (ex : 0 à 9) : ";
                $colonne = trim(fgets(STDIN));
                
                echo "Choisis le sens du placement du $nomBateauNum (v pour vertical ou h pour horizontal) : ";
                $sens = trim(strtolower(fgets(STDIN)));
                // normaliser quelques variantes d'entrée
                if ($sens === 'v') $sens = 'verticale';
                if ($sens === 'h') $sens = 'horizontal';
                
                // Essayer de placer avec bateaux()
                if (bateaux($grille, (int)$ligne, (int)$colonne, $sens, $taille, $nomBateauNum, $bateauxInfo)) {
                    $placed = true;
                    $totalPlaces++;
                    echo "✓ $nomBateauNum placé avec succès !\n";
                }
            }
        }
    }
    
    return true;
}

// Fonction pour vérifier si tous les bateaux sont coulés
function tousLesBateauxCoules(array $pvBateaux): bool {
    foreach ($pvBateaux as $pv) {
        if ($pv > 0) {
            return false;
        }
    }
    return true;
}

// Affiche l'état des PV de tous les bateaux
function affichePVTousLesBateaux(array $pvBateaux): void {
    echo "\n--- État de vos bateaux ---\n";
    foreach ($pvBateaux as $nomBateau => $pv) {
        if ($pv > 0) {
            echo "  $nomBateau : $pv PV\n";
        } else {
            echo "  $nomBateau : COULÉ ❌\n";
        }
    }
    echo "\n";
}

// Affiche une grille masquée (ne montre que touché=2 et raté=3)
function afficherGrilleMasquee(array $grille): void {
    echo "\nGrille (2=touché, 3=raté, 0=vide) :\n";
    // Afficher les numéros de colonnes
    echo "  ";
    for ($j = 0; $j < count($grille[0]); $j++) {
        echo $j . " ";
    }
    echo "\n";
    for ($i = 0; $i < count($grille); $i++) {
        echo $i . " ";
        for ($j = 0; $j < count($grille[0]); $j++) {
            if ($grille[$i][$j] == 2 || $grille[$i][$j] == 3) {
                echo $grille[$i][$j] . " ";
            } else {
                echo "0 ";
            }
        }
        echo "\n";
    }
    echo "------------\n";
}

// Fonction pour jouer en deux joueurs (tour par tour)
function jouerDeuxJoueurs(array $bateaux): void {
    // Initialisation des grilles et des infos
    $grille1 = initialisegrille(10,10);
    $bateauxInfo1 = initialisegrille(10,10);
    $grille2 = initialisegrille(10,10);
    $bateauxInfo2 = initialisegrille(10,10);

    // Placement pour chaque joueur (auto ou manuel)
    for ($joueur = 1; $joueur <= 2; $joueur++) {
        echo "\n--- Placement pour le Joueur $joueur ---\n";
        echo "Choisissez le placement pour le Joueur $joueur :\n";
        echo "1 - Automatique\n";
        echo "2 - Manuel\n";
        echo "Choix (1 ou 2) : ";
        $choix = trim(fgets(STDIN));

        if ($joueur === 1) {
            if ($choix === '1') {
                placerTousLesBateauxAuto($grille1, $bateauxInfo1, $bateaux);
            } else {
                placerTousLesBateaux($grille1, $bateauxInfo1, $bateaux);
            }
        } else {
            if ($choix === '1') {
                placerTousLesBateauxAuto($grille2, $bateauxInfo2, $bateaux);
            } else {
                placerTousLesBateaux($grille2, $bateauxInfo2, $bateaux);
            }
        }

        echo "✓ Placement du Joueur $joueur terminé. Appuyez sur Entrée pour continuer...\n";
        trim(fgets(STDIN));
        // Nettoyer l'écran pour que le joueur suivant ne voie pas les informations
        clearScreen();
    }

    // Initialiser les PV pour chaque joueur
    $pv1 = initPVBateaux($bateaux);
    $pv2 = initPVBateaux($bateaux);

    // Tour par tour
    $tour = 0;
    while (true) {
        $tour++;
        // Joueur actif
        $actif = ($tour % 2 === 1) ? 1 : 2;
        $adverse = ($actif === 1) ? 2 : 1;

        echo "\n========== TOUR $tour : Joueur $actif ==========" . "\n";

        // Choisir coordonnées
        echo "Quelle ligne voulez-vous cibler (0-9) ? ";
        $ligne = (int)trim(fgets(STDIN));
        echo "Quelle colonne voulez-vous cibler (0-9) ? ";
        $colonne = (int)trim(fgets(STDIN));

        if ($ligne < 0 || $ligne > 9 || $colonne < 0 || $colonne > 9) {
            echo "❌ Coordonnées invalides ! Veuillez entrer des nombres entre 0 et 9.\n";
            continue;
        }

        // Effectuer le tir sur la grille adverse
        if ($adverse === 1) {
            // Tir sur la grille du Joueur 1 (mode caché pour ne pas révéler les PV/adversaire)
            $validShot = false;
            while (!$validShot) {
                $res = tire($ligne, $colonne, $grille1, $pv1, $bateauxInfo1, false);
                if ($res === false) {
                    echo "Vous avez déjà tiré sur cette case. Réessayez.\n";
                    // Redemander des coordonnées valides
                    echo "Quelle ligne voulez-vous cibler (0-9) ? ";
                    $ligne = (int)trim(fgets(STDIN));
                    echo "Quelle colonne voulez-vous cibler (0-9) ? ";
                    $colonne = (int)trim(fgets(STDIN));
                    if ($ligne < 0 || $ligne > 9 || $colonne < 0 || $colonne > 9) {
                        echo "❌ Coordonnées invalides ! Veuillez entrer des nombres entre 0 et 9.\n";
                        continue;
                    }
                    continue;
                }
                $validShot = true;
            }
            // Afficher la grille masquée de l'adversaire (propriétaire grille1)
            afficherGrilleMasquee($grille1);
            // Ne pas afficher les PV pour cacher les informations
            if (tousLesBateauxCoules($pv1)) {
                echo "\n🎉 Le Joueur $actif a gagné !\n";
                break;
            }
        } else {
            // Tir sur la grille du Joueur 2 (mode caché)
            $validShot = false;
            while (!$validShot) {
                $res = tire($ligne, $colonne, $grille2, $pv2, $bateauxInfo2, false);
                if ($res === false) {
                    echo "Vous avez déjà tiré sur cette case. Réessayez.\n";
                    // Redemander des coordonnées valides
                    echo "Quelle ligne voulez-vous cibler (0-9) ? ";
                    $ligne = (int)trim(fgets(STDIN));
                    echo "Quelle colonne voulez-vous cibler (0-9) ? ";
                    $colonne = (int)trim(fgets(STDIN));
                    if ($ligne < 0 || $ligne > 9 || $colonne < 0 || $colonne > 9) {
                        echo "❌ Coordonnées invalides ! Veuillez entrer des nombres entre 0 et 9.\n";
                        continue;
                    }
                    continue;
                }
                $validShot = true;
            }
            afficherGrilleMasquee($grille2);
            if (tousLesBateauxCoules($pv2)) {
                echo "\n🎉 Le Joueur $actif a gagné !\n";
                break;
            }
        }

        // Pause entre les tours pour éviter que le joueur suivant voie l'écran
        echo "Appuyez sur Entrée pour passer au joueur suivant...\n";
        trim(fgets(STDIN));
        clearScreen();
    }
}

// Menu pour choisir le mode de jeu
echo "========== BATAILLE NAVALE ==========\n";
echo "Choisis le mode de jeu :\n";
echo "1 - Solo (contre l'ordinateur)\n";
echo "2 - Deux joueurs\n";
echo "Choix (1 ou 2) : ";
$modeJeu = trim(fgets(STDIN));

if ($modeJeu == 1) {
    // ===== MODE SOLO =====
    echo "\n========== MODE SOLO ==========\n";
    
    // Initialisation de deux grilles : une pour l'ordinateur, une pour le joueur
    $grilleOrdi = initialisegrille(10, 10);
    $bateauxInfoOrdi = initialisegrille(10, 10);
    
    $grilleJoueur = initialisegrille(10, 10);
    $bateauxInfoJoueur = initialisegrille(10, 10);
    
    echo "L'ordinateur place ses bateaux...\n";
    
    // Placement automatique caché des bateaux de l'ordinateur
    if (!placerTousLesBateauxAuto($grilleOrdi, $bateauxInfoOrdi, $bateaux)) {
        echo "Erreur : impossible de placer les bateaux de l'ordinateur.\n";
        exit;
    }
    
    echo "✓ Les bateaux de l'ordinateur sont en place !\n\n";
    
    // Choix de placement pour le joueur
    echo "À votre tour de placer vos bateaux :\n";
    echo "1 - Automatique\n";
    echo "2 - Manuel\n";
    echo "Choix (1 ou 2) : ";
    $choixPlacement = trim(fgets(STDIN));
    
    if ($choixPlacement === '1') {
        if (!placerTousLesBateauxAuto($grilleJoueur, $bateauxInfoJoueur, $bateaux)) {
            echo "Erreur : impossible de placer vos bateaux.\n";
            exit;
        }
        echo "✓ Vos bateaux sont placés automatiquement !\n\n";
    } else {
        if (!placerTousLesBateaux($grilleJoueur, $bateauxInfoJoueur, $bateaux)) {
            echo "Erreur : impossible de placer vos bateaux.\n";
            exit;
        }
        echo "✓ Vos bateaux sont maintenant en place !\n\n";
    }
    
    // Initialiser les PV des bateaux pour les deux joueurs
    $pvOrdi = initPVBateaux($bateaux);
    $pvJoueur = initPVBateaux($bateaux);
    
    // Choix d'affichage pour le mode solo
    echo "Voulez-vous voir les PV de vos bateaux pendant la partie ?\n";
    echo "1 - Oui (détaillé, affiche noms et PV)\n";
    echo "2 - Non (seulement notification quand un bateau est coulé, sans nom)\n";
    echo "Choix (1 ou 2) : ";
    $affichageChoix = trim(fgets(STDIN));
    $showShipNames = ($affichageChoix === '1');
    echo "✓ Préparation terminée\n";
    if ($showShipNames) {
        affichePVTousLesBateaux($pvJoueur);
    } else {
        echo "Mode simplifié : seuls les coulés seront indiqués sans nom.\n";
    }
    
    // Boucle de jeu : le joueur tire jusqu'à destruction de tous les bateaux de l'ordinateur
    $nombreTirs = 0;
    $tursOrdi = 0;
    
    while (!tousLesBateauxCoules($pvOrdi) && !tousLesBateauxCoules($pvJoueur)) {
        echo "\n========== VOTRE TIR ==========\n";
        echo "Tir numéro " . ($nombreTirs + 1) . "\n";

        // Boucle pour gérer les tirs invalides (cases déjà ciblées)
        while (true) {
            echo "Quelle ligne voulez-vous cibler sur la grille de l'ordinateur (0-9) ? ";
            $ligne = (int)trim(fgets(STDIN));

            echo "Quelle colonne voulez-vous cibler (0-9) ? ";
            $colonne = (int)trim(fgets(STDIN));

            // Vérifier que les coordonnées sont valides
            if ($ligne < 0 || $ligne > 9 || $colonne < 0 || $colonne > 9) {
                echo "❌ Coordonnées invalides ! Veuillez entrer des nombres entre 0 et 9.\n";
                continue;
            }

            // Effectuer le tir sur la grille de l'ordinateur
            echo "\nTir sur ($ligne, $colonne)...\n";
            $res = tire($ligne, $colonne, $grilleOrdi, $pvOrdi, $bateauxInfoOrdi, $showShipNames);

            if ($res === false) {
                // Tir invalide (case déjà ciblée) — ne pas incrémenter le compteur, demander de rejouer
                echo "Vous avez déjà tiré ici. Veuillez choisir une autre case.\n";
                continue;
            }

            // Tir valide — incrémenter le nombre de tirs et sortir de la boucle de saisie
            $nombreTirs++;
            break;
        }

        // Afficher l'état actuel de la grille de l'ordinateur (sans révéler les bateaux non touchés)
        echo "\nGrille de l'ordinateur (2=touché, 3=raté, 0=vide) :\n";
        // Afficher les numéros de colonnes
        echo "  ";
        for ($j = 0; $j < 10; $j++) {
            echo $j . " ";
        }
        echo "\n";
        // Afficher une grille masquée (on ne montre pas les bateaux intacts)
        for ($i = 0; $i < 10; $i++) {
            echo $i . " ";  // Numéro de ligne
            for ($j = 0; $j < 10; $j++) {
                if ($grilleOrdi[$i][$j] == 2 || $grilleOrdi[$i][$j] == 3) {
                    echo $grilleOrdi[$i][$j] . " ";
                } else {
                    echo "0 ";
                }
            }
            echo "\n";
        }
        echo "------------\n";

        // Afficher l'état des bateaux de l'ordinateur (selon le mode d'affichage choisi)
        if ($showShipNames) {
            affichePVTousLesBateaux($pvOrdi);
        }
        
        // Vérifier si l'ordinateur a gagné
        if (tousLesBateauxCoules($pvJoueur)) {
            echo "\n\n💻 ========== DÉFAITE ==========\n";
            echo "L'ordinateur a coulé tous vos bateaux en $tursOrdi tirs !\n";
            break;
        }
        
        // Tour de l'ordinateur : tir aléatoire sans répéter
        echo "\n========== TIR DE L'ORDINATEUR ==========\n";
        $validOrdiShot = false;
        while (!$validOrdiShot) {
            $ordiLigne = rand(0, 9);
            $ordiColonne = rand(0, 9);
            
            // Vérifier que la case n'a pas déjà été ciblée
            if ($grilleJoueur[$ordiLigne][$ordiColonne] != 2 && $grilleJoueur[$ordiLigne][$ordiColonne] != 3) {
                $validOrdiShot = true;
            }
        }
        
        echo "L'ordinateur tire sur ($ordiLigne, $ordiColonne)...\n";
        tire($ordiLigne, $ordiColonne, $grilleJoueur, $pvJoueur, $bateauxInfoJoueur, $showShipNames);
        $tursOrdi++;
        
        // Afficher l'état actuel de la grille du joueur
        echo "\nVotre grille (2=touché, 3=raté, 0=vide) :\n";
        // Afficher les numéros de colonnes
        echo "  ";
        for ($j = 0; $j < 10; $j++) {
            echo $j . " ";
        }
        echo "\n";
        // Afficher une grille masquée (on ne montre pas les bateaux intacts)
        for ($i = 0; $i < 10; $i++) {
            echo $i . " ";  // Numéro de ligne
            for ($j = 0; $j < 10; $j++) {
                if ($grilleJoueur[$i][$j] == 2 || $grilleJoueur[$i][$j] == 3) {
                    echo $grilleJoueur[$i][$j] . " ";
                } else {
                    echo "0 ";
                }
            }
            echo "\n";
        }
        echo "------------\n";

        // Afficher l'état de vos bateaux (selon le mode d'affichage choisi)
        if ($showShipNames) {
            affichePVTousLesBateaux($pvJoueur);
        }
        
        // Vérifier si le joueur a gagné
        if (tousLesBateauxCoules($pvOrdi)) {
            echo "\n\n🎉 ========== VICTOIRE !==========\n";
            echo "Bravo ! Vous avez détruit tous les bateaux de l'ordinateur en $nombreTirs tirs !\n";
            break;
        }
        
        echo "\nAppuyez sur Entrée pour continuer...\n";
        trim(fgets(STDIN));
    }

} else if ($modeJeu == 2) {
    // ===== MODE DEUX JOUEURS =====
    echo "\n========== MODE DEUX JOUEURS ==========" . "\n";
    jouerDeuxJoueurs($bateaux);

} else {
    echo "Choix invalide ! Arrêt du programme.\n";
    exit;
}