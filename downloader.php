<?php
set_time_limit(0);
date_default_timezone_set('Europe/Madrid');
define('BASE_URL', 'https://www.free-css.com/');

require './vendor/autoload.php';

$dom = new PHPHtmlParser\Dom;

if (!is_dir('./templates'))
    mkdir('./templates', 0775, true);


$count = 0;
$time = microtime(true);

for ($i = 290; $i <= 290; $i--) {
    $page = BASE_URL . 'free-css-templates/page' . $i;

    $dom->loadFromFile($page);

    $showcase = $dom->find('#showcase');

    // $dom = null;

    $clear = $showcase->find('ul.clear');

    $showcase = null;

    $lis = $clear->find('li');

    $clear = null;

    foreach ($lis as $li) {
        $a = $li->firstChild()->find('a');

        $title = trim(str_replace('Website Template', '', $a->getAttribute('title')));
        $title = str_replace(' ', '_', $title);

        $template_url = str_replace('free-css-templates/', 'free-css-templates/download/', $a->getAttribute('href'));
        $template_url = str_replace('//', '/', BASE_URL . 'assets/files/' . $template_url);

        echo $template_url . '<br>';

        if (!is_dir('./templates/' . $title))
            mkdir('./templates/' . $title, 0775, true);

        // $res = file_put_contents('./templates/' . $title . '/' . $title . '.zip', fopen($template_url . '.zip', 'r'));

        $ch = curl_init();
        $source = $template_url;
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $destination = './templates/' . $title . '/' . $title . '.zip';
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);

    }

}

$end = microtime(true);
$time = $end - $start;
echo "Tiempo de ejecución: (<i>$time</i>) segundos";