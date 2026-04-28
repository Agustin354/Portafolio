<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

function scrape(string $url, string $selector): array
{
    $client = new Client(['headers' => ['User-Agent' => 'Mozilla/5.0']]);
    $html   = (string)$client->get($url)->getBody();
    $crawler = new Crawler($html);

    return $crawler->filter($selector)->each(fn(Crawler $nodo) => [
        'texto' => trim($nodo->text()),
        'href'  => $nodo->attr('href'),
    ]);
}

function guardar_json(array $datos, string $archivo): void
{
    file_put_contents($archivo, json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "Guardado en $archivo\n";
}

function guardar_csv(array $datos, string $archivo): void
{
    if (empty($datos)) return;
    $fp = fopen($archivo, 'w');
    fputcsv($fp, array_keys($datos[0]));
    foreach ($datos as $fila) fputcsv($fp, $fila);
    fclose($fp);
    echo "Guardado en $archivo\n";
}

echo 'URL: ';
$url = trim(fgets(STDIN));
echo 'Selector CSS (ej: a, h2, .clase): ';
$selector = trim(fgets(STDIN));

$datos = scrape($url, $selector);
echo "Encontrados: " . count($datos) . " elementos\n";
foreach (array_slice($datos, 0, 5) as $d) {
    echo $d['texto'] . "\n";
}

guardar_json($datos, 'resultado.json');
guardar_csv($datos, 'resultado.csv');
