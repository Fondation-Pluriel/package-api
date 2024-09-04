<?php

// Définir le répertoire source (dans le package)
$sourceDir = dirname(__DIR__) . '/config';

// Définir le répertoire de destination (dans le projet)
$destinationDir = dirname(__DIR__, 5) . '/config';

// Fonction pour copier les fichiers et les dossiers récursivement
function copyDir($src, $dst) {
    // Vérifier si le répertoire source existe
    if (!is_dir($src)) {
        throw new InvalidArgumentException("Le répertoire source '$src' n'existe pas.");
    }

    // Ouvrir le répertoire source
    $dir = opendir($src);

    // Créer le répertoire de destination s'il n'existe pas
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }

    // Lire les fichiers et dossiers dans le répertoire source
    while (($file = readdir($dir)) !== false) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        $srcPath = $src . '/' . $file;
        $dstPath = $dst . '/' . $file;

        if (is_dir($srcPath)) {
            // Si c'est un répertoire, appeler la fonction récursivement
            copyDir($srcPath, $dstPath);
        } else {
            // Si c'est un fichier, le copier dans le répertoire de destination
            copy($srcPath, $dstPath);
        }
    }

    // Fermer le répertoire
    closedir($dir);
}

// Appel de la fonction de copie
try {
    copyDir($sourceDir, $destinationDir);
    echo "Les fichiers de configuration ont été copiés avec succès.\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
