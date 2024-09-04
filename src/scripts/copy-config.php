<?php

// Définir le répertoire source (dans le package)
$sourceDir = __DIR__ . '/config';

// Définir le répertoire de destination (dans le projet)
$destinationDir = dirname(__DIR__, 3) . '/config';

// Fonction pour copier les fichiers et les dossiers récursivement
function copyDir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (($file = readdir($dir)) !== false) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Appel de la fonction de copie
copyDir($sourceDir, $destinationDir);

echo "Les fichiers de configuration ont été copiés avec succès.\n";
