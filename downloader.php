<?php
set_time_limit(0);
date_default_timezone_set('Europe/Madrid');

define('BASE_URL', 'https://www.free-css.com/');
define('TEMPLATES_DIR', './templates');

require './vendor/autoload.php';

if (!is_dir(TEMPLATES_DIR)) {
    mkdir(TEMPLATES_DIR, 0775, true);
}

$dom = new PHPHtmlParser\Dom;

$ok             = 0;
$exist          = 0;
$exceptionCount = 0;
$errors         = [];
$start          = microtime(true);
$latestPage     = 294;

for ($i = $latestPage; $i > 0; $i--) {
    $page = BASE_URL . 'free-css-templates/page' . $i . '/';

    try {
        $dom->loadFromFile($page);
    } catch (Exception $e) {
        $exceptionCount++;
    }

    $showcase = $dom->find('#showcase');

    // $dom = null;

    $clear = $showcase->find('ul.clear');

    $showcase = null;

    $lis = $clear->find('li');

    $clear = null;

    foreach ($lis as $li) {
        $a = $li->firstChild()->find('a');

        $title = trim(str_replace('Website Template', '', $a->getAttribute('title')));
        $title = str_replace([' ', '.'], '_', $title);

        $template_url = str_replace('free-css-templates/', 'free-css-templates/download/', $a->getAttribute('href'));
        $template_url = str_replace('//', '/', BASE_URL . 'assets/files/' . $template_url);

        echo $template_url;

        if (!is_dir(TEMPLATES_DIR . '/' . $title)) {
            mkdir(TEMPLATES_DIR . '/' . $title, 0775, true);
        }

        if (!file_exists(TEMPLATES_DIR . '/' . $title . '/' . $title . '.zip')) {

            // $res = file_put_contents('./templates/' . $title . '/' . $title . '.zip', fopen($template_url . '.zip', 'r'));

            $ch     = curl_init();
            $source = $template_url;
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);

            try {
                $destination = TEMPLATES_DIR . '/' . $title . '/' . $title . '.zip';
                $file        = fopen($destination, "w+");

                fputs($file, $data);
                fclose($file);
                $ok++;

                echo PHP_EOL;
                echo 'OK';
                echo PHP_EOL;
                echo PHP_EOL;
            } catch (Exception $e) {
                $errors[] = $source;
                echo PHP_EOL;
                echo 'ERROR';
                echo PHP_EOL;
                echo PHP_EOL;
            }

            echo 'EXIST -> ' . $exist . ' :: OK -> ' . $ok . ' :: ERR -> ' . count($errors);
            echo PHP_EOL;
            echo PHP_EOL;

        } else {
            $exist++;
            echo PHP_EOL;
            echo 'EXIST -> ' . $exist . ' :: OK -> ' . $ok . ' :: ERR -> ' . count($errors);
            echo PHP_EOL;
            echo PHP_EOL;
        }


    }

}

$end  = microtime(true);
$time = $end - $start;
echo PHP_EOL;
echo PHP_EOL;
echo "Tiempo de ejecución: ($time) segundos";
echo PHP_EOL;

exit;
