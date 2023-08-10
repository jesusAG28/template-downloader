<?php
set_time_limit(0);
date_default_timezone_set('Europe/Madrid');

define('BASE_URL', 'https://www.free-css.com/');
define('TEMPLATES_DIR', './templates');

require './vendor/autoload.php';

if (!is_dir(TEMPLATES_DIR)) {
    mkdir(TEMPLATES_DIR, 0775, true);
}

$folders     = scandir(TEMPLATES_DIR);
$unzipErrors = [];
$unzipped    = 0;

if (!$folders) {
    // Si no hay contenido corto ejecucion
    die('Carpeta vacia');
} else {
    $start = microtime(true);

    // Elimino las entradas '..' y '.'
    $folders = array_diff($folders, ['..', '.']);

    foreach ($folders as $folder) {
        $fileName  = $folder . '.zip';
        $fileRoute = TEMPLATES_DIR . '/' . $folder . '/' . $fileName;

        echo $fileRoute;

        if (file_exists($fileRoute)) {

            $zip = new ZipArchive();

            if ($zip->open($fileRoute)) {
                $zip->extractTo(TEMPLATES_DIR . '/' . $folder);
                $unzipped++;

                echo PHP_EOL;
                echo 'OK';
                echo PHP_EOL;
                echo PHP_EOL;
            } else {
                $unzipErrors[] = [
                    'route' => $fileRoute,
                    'error' => 'No se pudo abrir el archivo',
                ];

                echo PHP_EOL;
                echo 'ERROR';
                echo PHP_EOL;
                echo PHP_EOL;
            }

            echo 'UNZIPPED -> ' . $unzipped . ' :: ERR -> ' . count($unzipErrors);
            echo PHP_EOL;
            echo PHP_EOL;

            $zip->close();

            $zip = null;

        } else {
            $unzipErrors[] = [
                'route' => $fileRoute,
                'error' => 'Archivo no existe',
            ];
        }
    }

    $end  = microtime(true);
    $time = $end - $start;
    echo PHP_EOL;
    echo PHP_EOL;
    echo "Tiempo de ejecución: ($time) segundos";
    echo PHP_EOL;
}

exit;
